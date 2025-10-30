<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    const int PAGINATION_LIMIT = 15;
    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->invoiceService->paginateInvoices(self::PAGINATION_LIMIT);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateInvoiceData = $request->validate([
                "number" => "required|string|unique:invoices,number|max:50",
                "customer_name" => "required|string|max:255",
                "customer_email" => "required|email",
                "date" => "required|date",
                "due_date" => "required|date|gte:date",
                "status" => "required|string|in:unpaid,paid,draft",
                "items" => "required|array|min:1",
                "item.*.description" => "required|string|max:500",
                "item.*.quantity" => "required|integer|min:1",
                "item.*.unit_price" => "required|numeric|min:0",
            ]
        );

        $items = $validateInvoiceData['items'];

        $invoiceToSend = $this->invoiceService->createInvoiceWithItems($validateInvoiceData, $items);

        if ($invoiceToSend == null) {
            return response()->json(['message' => 'Error creating invoice'], 500);
        }
        return response()->json($invoiceToSend->load('items'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = $this->invoiceService->getInvoiceById($id);
        return response()->json($invoice, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->invoiceService->deleteInvoice($id);
        return response()->noContent();
    }
}
