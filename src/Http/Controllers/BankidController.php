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
        return "This route you should replace with your 'dashboard' or 'logged in' controller::action";
    }
    public function cancel()
    {
        return "This route you should replace with your controller::action to use when user cancels";
    }
}
