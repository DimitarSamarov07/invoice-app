<?php

namespace App\Services;

use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function paginateInvoices(int $perPage){
        return Invoice::with("items")->paginate($perPage);
    }

    public function createInvoiceWithItems(array $invoiceData, array $items) : ?Invoice {

        try {
            return DB::transaction(function () use ($invoiceData, $items) {
                $subtotal = 0;
                foreach ($items as $invoiceItem){
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
        }
        catch (Exception){
            return null;
        }
    }

    public function getInvoiceById(int $id) : Invoice{
        return Invoice::with('items')->findOrFail($id);
    }

    public function deleteInvoice(int $id) : bool{
        return Invoice::destroy($id);
    }
}
