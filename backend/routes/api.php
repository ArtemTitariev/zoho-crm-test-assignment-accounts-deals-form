<?php

use App\Http\Controllers\ZohoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('zoho')->name('zoho.')->group(function () {
    Route::post('/create-deal-and-account', [ZohoController::class, 'createDealAndAccount'])->name('create-deal-and-account');
});
