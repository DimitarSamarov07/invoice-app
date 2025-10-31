<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvoiceService
{
    public function paginateInvoices(int $perPage, array $queries)
    {
        $queryForInvoices = Invoice::query();

        if (isset($queries['search'])) {
            // Make the search case-insensitive
            $searchTerm = strtolower($queries['search']);

            $queryForInvoices->whereRaw('LOWER(customer_name) LIKE ?', ['%' . $searchTerm . '%']);
        }

        if (isset($queries['status'])) {
            $queryForInvoices->where('status', $queries['status']);
        }

        return $queryForInvoices->paginate($perPage)->withQueryString();
    }

    public function createInvoiceWithItems(array $invoiceData, array $items): ?Invoice
    {

        try {
            return DB::transaction(function () use ($invoiceData, $items) {
                $subtotal = 0;
                foreach ($items as $invoiceItem) {
                    // There's no need to add the total to the DB item, as it's a calculated column.
                    $totalForItem = $invoiceItem['quantity'] * $invoiceItem['unit_price'];
                    $subtotal += $totalForItem;
                }

                $total = round($subtotal * 1.2, 2);
                $vat = $total - $subtotal;

                $invoiceData['subtotal'] = $subtotal;
                $invoiceData['vat'] = $vat;

                $newInvoice = Invoice::create($invoiceData);
                $newInvoice->items()->createMany($items);
            });
        } catch (Throwable) {
            return null;
        }
    }

    public function deleteInvoice(int $id): void
    {
        $invoice = $this->getInvoiceById($id);
        $invoice::delete();
    }

    public function getInvoiceById(int $id): Invoice
    {
        return Invoice::with('items')->findOrFail($id);
    }

    public function updateInvoice(string $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $invoice = $this->getInvoiceById($id);
                $invoice->update($data);
                $invoice->items()->delete();
                $invoice->items()->createMany($data['items']);

                $subtotal = $invoice->items()->sum('total');
                $vat = round($subtotal * 0.2, 2);
                $invoice->subtotal = $subtotal;
                $invoice->vat = $vat;
                $invoice->save();
                return $invoice->load('items');
            });
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable) {
            return null;
        }
    }

    public function patchInvoice(string $id, array $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $invoice = $this->getInvoiceById($id);
                $invoice->update($data);

                if (isset($data['items'])) {
                    $IDsOfProcessedItems = [];
                    foreach ($data['items'] as $item) {
                        if (isset($item['id'])) {
                            // Retrieval through the relationship ensures that no unrelated items can be updated.
                            $itemRetrieved = $invoice->items()->find($item['id']);
                            if (!$itemRetrieved) {
                                continue;
                            }
                            $itemRetrieved->update($item);
                            $IDsOfProcessedItems[] = $itemRetrieved->id;
                        } else {
                            $newItem = $invoice->items()->create($item);
                            $IDsOfProcessedItems[] = $newItem->id;
                        }
                    }

                    // This ensures that items that are no longer present in the "items" array are deleted.
                    $invoice->items()->whereNotIn('id', $IDsOfProcessedItems)->delete();

                    $subtotal = $invoice->items()->sum('total');
                    $vat = round($subtotal * 0.2, 2);
                    $invoice->subtotal = $subtotal;
                    $invoice->vat = $vat;
                    $invoice->save();
                }

                return $invoice->load('items');
            });
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable) {
            return null;
        }

    }
}
