<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Controllers;

use Illuminate\Routing\Controller;

class BankidController extends Controller
{

    public function index() {
        return view('index');
    }

    public function complete()
    {
        if (config("bankid.completeUrl") && ! empty(config("bankid.completeUrl"))) {
            return redirect(config("bankid.completeUrl"));
        }

        return "Fill out completeUrl in your config/bankid.php or .env file. If you are missing config/bankid.php then run php artisan vendor:publish ";
    }

    public function cancel()
    {
        if (config("bankid.cancelUrl") && ! empty(config("bankid.cancelUrl"))) {
            return redirect(config("bankid.cancelUrl"));
        }
        return "Fill out cancelUrl in your config/bankid.php or .env file. ";
    }
}
