<?php

use App\Http\Controllers\API\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/invoices", [InvoiceController::class, 'index']);
Route::get("/invoices/{id}", [InvoiceController::class, 'show']);

Route::post("/invoices", [InvoiceController::class, 'store']);

Route::put("/invoices/{id}", [InvoiceController::class, 'update']);
Route::patch("/invoices/{id}", [InvoiceController::class, 'update']);

Route::delete("/invoices/{id}", [InvoiceController::class, 'destroy']);
