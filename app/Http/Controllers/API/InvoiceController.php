<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\GetInvoicesRequest;
use App\Http\Requests\PatchUpdateInvoiceRequest;
use App\Http\Requests\PutUpdateInvoiceRequest;
use App\Services\InvoiceService;

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
    public function index(GetInvoicesRequest $request)
    {
        $validated = $request->validated();
        return $this->invoiceService->paginateInvoices(self::PAGINATION_LIMIT, $validated);
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
        if (!$invoice){
            return response()->json(['message' => 'Invoice not found'], 404);
        }
        return response()->json($invoice, 200);
    }

    /**
     * Patch updates the specified resource in storage.
     */
    public function patch(PatchUpdateInvoiceRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $patchedInvoice = $this->invoiceService->patchInvoice($id, $validatedData);

        if ($patchedInvoice == null) {
            // If anything goes wrong, return a 500 error. The 404 cases are handled by the validation.
            return response()->json(['message' => 'Error patching invoice. This could be caused by user error or a server fault.'], 500);
        }

        return response()->json($patchedInvoice);
    }

    /**
     * Replaces the specified resource with new data in storage.
     */
    public function update(PutUpdateInvoiceRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $updatedInvoice = $this->invoiceService->updateInvoice($id, $validatedData);

        if ($updatedInvoice == null) {
            // If anything goes wrong, return a 500 error. The 404 cases are handled by the validation.
            return response()->json(['message' => 'Error updating invoice. This could be caused by user error or a server fault.'], 500);
        }

        return response()->json($updatedInvoice);
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
