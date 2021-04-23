<?php

namespace Patrikgrinsvall\LaravelBankid;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Patrikgrinsvall\LaravelBankid\LaravelBankid
 */
class BankidFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bankid';
    }
}
