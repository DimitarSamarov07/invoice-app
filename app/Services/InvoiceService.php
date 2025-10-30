<?php

namespace App\Services;

use App\Models\Invoice;
use Exception;
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

        if (isset($filters['status'])) {
            $queryForInvoices->where('status', $filters['status']);
        }

        return $queryForInvoices->paginate($perPage)->withQueryString();
    }

    public function createInvoiceWithItems(array $invoiceData, array $items): ?Invoice
    {

        try {
            return DB::transaction(function () use ($invoiceData, $items) {
                $subtotal = 0;
                foreach ($items as $invoiceItem) {
                    $totalForItem = $invoiceItem['quantity'] * $invoiceItem['unit_price'];
                    $subtotal += $totalForItem;
                    $invoiceItem['total'] = $totalForItem;
                }

                $total = round($subtotal * 1.2, 2);
                $vat = $total - $subtotal;

                $invoiceData['subtotal'] = $subtotal;
                $invoiceData['vat'] = $vat;
                $invoiceData['total'] = $total;

                $newInvoice = Invoice::create($invoiceData);
                $newInvoice::items()->createMany($items);
            });
        } catch (Exception) {
            return null;
        }
    }

    public function getInvoiceById(int $id): Invoice
    {
        return Invoice::with('items')->findOrFail($id);
    }


    public function deleteInvoice(int $id): void
    {
        $invoice = $this->getInvoiceById($id);
        $invoice::delete();
    }

    public function updateInvoice(array $data){
        try {
            return DB::transaction(function () use ($data) {
                $invoice = $this->getInvoiceById($data['id']);
                $invoice->update($data);
                $invoice->items()->delete();
                $invoice->items()->createMany($data['items']);
            });
        }
        catch (Throwable) {
            return null;
        }
    }

    public function patchInvoice(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $invoice = $this->getInvoiceById($data['id']);
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
                }

                return $invoice->load('items');
            });
        } catch (Throwable) {
            return null;
        }

    }
}
