<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Livewire\Component;

class BankidComponent extends Component
{
    const BANKID_CODES = [
        "INVALID_PARAMETERS"        => "INVALID_PARAMETERS",
        "ALREADY_IN_PROGRESS"       => "ALREADY_IN_PROGRESS",
        "INTERNAL_ERROR"            => "INTERNAL_ERROR",
        "OUTSTANDING_TRANSACTION"   => "OUTSTANDING_TRANSACTION",
        "NO_CLIENT"                 => "NO_CLIENT",
        "STARTED"                   => "STARTED",
        "USER_SIGN"                 => "USER_SIGN",
        "COMPLETE"                  => "COMPLETE",
        "USER_CANCEL"               => "USER_CANCEL",
        "CANCEL"                    => "CANCELLED",
        "EXPIRED_TRANSACTION"       => "EXPIRED_TRANSACTION",
    ];

    public $message = "Enter personal number";

    public function __construct($id = null)
    {
        $this->init();
        parent::__construct($id = null);

    }

    public function check_configuration()
    {
        if(empty(config("bankid.ENDPOINT")))
            throw new \Exception('Environment variable ENDPOINT missing. Bankid endpoint not specified', 2);
        if(empty(config("bankid.SSL_CERT")))
            throw new \Exception('Environment variable SSL_CERT missing. Bankid local certificate not configured', 2);
        if(empty(config("bankid.SSL_KEY")))
            throw new \Exception('Environment variable SSL_KEY missing. Bankid key file not configured', 2);
        if(empty(config("bankid.SSL_KEY_PASSWORD")))
            throw new \Exception('Environment variable SSL_KEY_PASSWORD missing. Bankid key file password not configured', 2);
        if(empty(config("bankid.CA_CERT")))
            throw new \Exception('Environment variable CA_CERT missing. CA cert not specified', 2);

    }



    public function collect()
    {

    }

    public function authenticate()
    {
        $this->message = array_rand(self::BANKID_CODES);
    }

    public function render()
    {

        // this is taken from project now, this should be published to resource folder
        return view('livewire.bankid-personalnumber');
    }
}
