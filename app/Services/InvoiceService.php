<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvoiceService
{
    /**
     * Paginate invoices with optional filtering by search term and status.
     *
     * @param int $perPage Number of items to display per page.
     * @param array $queries Array of query parameters to filter the results.
     *                       It can include 'search' for querying by customer name and 'status' for filtering by invoice status.
     * @return LengthAwarePaginator Paginated list of invoices with their associated items.
     */
    public function paginateInvoices(int $perPage, array $queries): LengthAwarePaginator
    {
        $queryForInvoices = Invoice::query()->with('items');

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

    /**
     * Creates a new invoice along with its associated items.
     *
     * This method uses a transaction to ensure that both the invoice and its items are either
     * successfully created together or not created at all in case of an error.
     *
     * @param array $invoiceData The data for the invoice, including any necessary attributes such as customer details or invoice metadata.
     * @param array $items The list of items to be associated with the invoice, each containing attributes like quantity and unit price.
     * @return Invoice|null The newly created invoice model with its associated items loaded, or null if an error occurs during creation.
     */
    public function createInvoiceWithItems(array $invoiceData, array $items): ?Invoice
    {

        try {
            // Use a transaction to ensure that both the invoice and its items are created or none of them.
            return DB::transaction(function () use ($invoiceData, $items) {
                $subtotal = 0;
                foreach ($items as $invoiceItem) {
                    // There's no need to add the total to the DB item, as it's a calculated column.
                    $totalForItem = $invoiceItem['quantity'] * $invoiceItem['unit_price'];
                    $subtotal += $totalForItem;
                }

                // Calculate VAT and total
                $total = round($subtotal * 1.2, 2);
                $vat = $total - $subtotal;

                $invoiceData['subtotal'] = $subtotal;
                $invoiceData['vat'] = $vat;

                // Create the invoice and its items.
                $newInvoice = Invoice::create($invoiceData);
                $newInvoice->items()->createMany($items);

                // Make sure to load the items relationship to ensure that they are included in the response.
                return $newInvoice->load('items');
            });
        } catch (Throwable) {
            // Handle any exceptions that occur during the transaction.
            return null;
        }
    }

    /**
     * Deletes an invoice by its unique identifier.
     *
     * @param int $id The unique identifier of the invoice to be deleted.
     * @return void
     */
    public function deleteInvoice(int $id): void
    {
        $invoice = $this->getInvoiceById($id);
        $invoice->delete();
    }

    /**
     * Retrieves an invoice by its ID and loads its related items.
     *
     * @param int $id The unique identifier of the invoice to retrieve.
     * @return Invoice The invoice object with its associated items.
     * @throws ModelNotFoundException If no invoice with the specified ID is found. Laravel can handle this exception automatically.
     */
    public function getInvoiceById(int $id): Invoice
    {
        return Invoice::with('items')->findOrFail($id);
    }

    /**
     * Updates an existing invoice with the given data, including its associated items.
     * Calculates and updates the subtotal and VAT based on the updated items.
     *
     * @param string $id The identifier of the invoice to be updated.
     * @param array $data The data used to update the invoice. Must include updated attributes and an array of items.
     *
     * @return Model|null The updated invoice with its associated items, or null if an error occurs.
     *
     * @throws ModelNotFoundException If the invoice with the given ID is not found.
     */
    public function updateInvoice(string $id, array $data) : Model | null
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
            // This is so Laravel can handle the exception automatically in the controller.
            throw $e;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Updates an existing invoice record with the provided data and its associated items if applicable.
     * The method ensures that only related items are updated or deleted, and recalculates the invoice's subtotal and VAT.
     *
     * @param string $id The unique identifier of the invoice to be updated.
     * @param array $data The data to update the invoice with, including optional items data.
     * @return mixed The updated invoice with its associated items, or null if an error occurs during the process.
     * @throws ModelNotFoundException If the invoice with the specified ID is not found.
     */
    public function patchInvoice(string $id, array $data): mixed
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
