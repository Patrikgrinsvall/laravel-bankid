<?php

use Illuminate\Support\Facades\Route;

Route::prefix('bankid')
     ->middleware(['auth:sanctum', 'verified'])
     ->group(function () {

         Route::get('/', function () {
             return view('welcome');
         });
         Route::resource(
             'bankid-integrations',
             \Patrikgrinsvall\LaravelBankid\Http\Controllers\BankidIntegrationController::class
         );
     });
