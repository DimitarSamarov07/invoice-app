<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string("customer_name");
            $table->string("customer_email");
            $table->date("due_date");
            $table->decimal("subtotal", 10, 2);
            $table->decimal("vat", 10, 2);
            $table->decimal("total", 10, 2);
            $table->enum("status", ["unpaid", "paid", "draft"]);
            $table->timestamp("created_at");
            $table->timestamp("updated_at");
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
