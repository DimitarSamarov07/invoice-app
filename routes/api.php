<?php

use App\Http\Controllers\API\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/invoices", function () {

});

Route::get("/invoices/{id}", function () {

});

Route::post("/invoices", [InvoiceController::class, 'store']);

Route::put("/invoices/{id}", function () {

});

