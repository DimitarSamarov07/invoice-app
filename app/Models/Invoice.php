<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'number', "customer_name", "customer_email", "date",
        "due_date", "subtotal", "vat", "status"
    ];

    public function items() : HasMany {
        return $this->hasMany(InvoiceItem::class);
    }
}
