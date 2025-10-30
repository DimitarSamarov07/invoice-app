<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
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
    public function store(CreateInvoiceRequest $request)
    {
        $validateInvoiceData = $request->validated();

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
    public function update(UpdateInvoiceRequest $request, string $id)
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
