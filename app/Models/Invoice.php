<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'number', "customer_name", "customer_email", "date",
        "due_date", "subtotal", "vat", "total", "status"
    ];

    public function items() : HasMany {
        return $this->hasMany(InvoiceItem::class);
    }
}
