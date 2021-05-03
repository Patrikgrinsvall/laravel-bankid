<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Controllers;

use Illuminate\Routing\Controller;

class BankidController extends Controller
{
    public function index()
    {
        return view('LaravelBankid::bankidindex');
    }

    public function bankidLogin()
    {
        return view('bankidlogin');
    }
    public function complete()
    {
        if(config("bankid.completeUrl") && !empty(config("bankid.completeUrl"))) return redirect(config("bankid.completeUrl"));
        return "Fill out completeUrl in your config/bankid.php. If you are missing config/bankid.php then run php artisan vendor:publish ";
    }
    public function cancel()
    {
        return "This route you should replace with your controller::action to use when user cancels";
    }
}
