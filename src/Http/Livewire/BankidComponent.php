<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Livewire\Component;
use Patrikgrinsvall\LaravelBankid\Bankid;

class BankidComponent extends Component
{
    private $bankid;

    protected $rules = [
        'personalNumber' => 'required|min:12'
    ];

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
        $result = $this->bankid->authenticate(null);
        $this->message = $result['message'];
    }

    public function render()
    {

        // this is taken from project now, this should be published to resource folder
        return view('livewire.bankid-personalnumber');
    }
}
