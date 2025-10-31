<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "number" => "required|string|unique:invoices,number|max:50",
            "customer_name" => "required|string|max:255",
            "customer_email" => "required|email",
            "date" => "required|date",
            "due_date" => "required|date|gte:date",
            "status" => "required|string|in:unpaid,paid,draft",
            "items" => "required|array|min:1",
            "items.*.description" => "required|string|max:500",
            "items.*.quantity" => "required|integer|min:1",
            "items.*.unit_price" => "required|numeric|min:0",
        ];
    }
}
