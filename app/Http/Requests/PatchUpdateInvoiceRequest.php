<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PatchUpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "id" => "required|exists:invoices,id",
            "number" => "sometimes|string|unique:invoices,number|max:50",
            "customer_name" => "sometimes|string|max:255",
            "customer_email" => "sometimes|email",
            "date" => "sometimes|date",
            "due_date" => "sometimes|date|gte:date",
            "status" => "sometimes|string|in:unpaid,paid,draft",
            "items" => "sometimes|array|min:1",
            "item.*.id" => "sometimes|exists:invoice_items,id",
            "item.*.description" => "sometimes|string|max:500",
            "item.*.quantity" => "sometimes|integer|min:1",
            "item.*.unit_price" => "sometimes|numeric|min:0",
        ];
    }
}
