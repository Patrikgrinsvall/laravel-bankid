<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Livewire\Component;
use Patrikgrinsvall\LaravelBankid\Bankid;

class BankidComponent extends Component
{
    private $bankid;

    public function __construct($id = null)
    {
        $this->bankid = new Bankid();
        $this->bankid->check_configuration();
        parent::__construct($id = null);
    }

    public function collect()
    {

    }

    public $message = "Enter personal number";

    public function authenticate()
    {
        $this->bankid->authenticate();
    }

    public function render()
    {

        // this is taken from project now, this should be published to resource folder
        return view('livewire.bankid-personalnumber');
    }
}
