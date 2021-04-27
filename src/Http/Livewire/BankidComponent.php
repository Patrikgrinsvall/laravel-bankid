<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Patrikgrinsvall\LaravelBankid\Bankid;

class BankidComponent extends Component
{
    private $bankid;                                                                        // external dependency.
    const DEFAULT_PERSONALNUMBER = "193204101488";                                       // default value for personal number
    protected $rules = [ 'personalNumber' => 'required|min:12' ];            // validation rules
    public $message = "Enter personal number";                              // default message
    public $status = "WAITING";
    public $personalNumber = self::DEFAULT_PERSONALNUMBER;                         // inputbox prewritten personalNumber
    protected $listeners = [ 'personalNumberClick' => 'personalNumberClick' ];   // event for clicking input
    public $currentState = "";                                                   // current state
    const STATES = ['WAITING', 'COLLECTING', 'ERROR', 'COMPLETE'];       // not used but we should have state machine
    public $orderRef = "";

    /**
     * Initialize bankid here so we crash early if something missconfigured. (maybe not good for unit test @todo refactor)
     *
     * @param UUID $id
     */
    public function __construct($id = null)
    {
        $this->bankid = new Bankid();
        $this->bankid->check_configuration();
        parent::__construct($id = null);
    }

    /**
     * Event when personalNumberfield is clicked
     *
     * @return void
     */
    public function personalNumberClick()
    {
        if ($this->personalNumber === self::DEFAULT_PERSONALNUMBER) {
            $this->personalNumber = "";
        }
    }

    /**
     * Collect result for sign or auth
     *
     * @return void
     */
    public function collect()
    {
        if (Arr::exists(['pending' => 'true','collect' => 'true'], $this->status) === true) {
            $result = $this->bankid->collect(['orderRef' => $this->orderRef]);

            $this->updateState($result);
            if ($result['status'] == 'complete') {
                $this->message .= "<script>setTimeout(function(){window.location='/bankid/complete'},2000);</script>";
            }
        } else {
            Log::error("not exit". $this->status);
        }
    }

    /**
     * Update internal state with bankid response
     *
     * @param [type] $result
     * @return void
     */
    private function updateState(array $result)
    {
        foreach ($result as $key => $val) {
            $key = trim($key);
            $this->$key = trim($val);
            Log::error("setting $key to $val");
        }
    }

    /**
     * Start an auth
     *
     * @return void
     */
    public function authenticate()
    {
        $result = $this->bankid->Authenticate($this->personalNumber);
        $this->updateState($result);
    }

    /**
     * Display view
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.bankid-personalnumber'); // this is taken from project now, this should be published to resource folder
    }
}
