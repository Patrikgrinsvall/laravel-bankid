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
}
