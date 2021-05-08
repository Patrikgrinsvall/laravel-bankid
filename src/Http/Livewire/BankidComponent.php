<?php

namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Patrikgrinsvall\LaravelBankid\Bankid;
use Illuminate\Support\Collection;
use Patrikgrinsvall\LaravelBankid\Models\BankidResult;

class BankidComponent extends Component
{
    private $bankid;                                                                        // external dependency.
    const DEFAULT_PERSONALNUMBER = "193204101488";                                       // default value for personal number
    protected $rules = [ 'personalNumber' => 'required|min:12' ];            // validation rules
    public $personalNumber = self::DEFAULT_PERSONALNUMBER;                         // inputbox prewritten personalNumber
    protected $listeners = [ 'personalNumberClick' => 'personalNumberClick' ];   // event for clicking input
    public $status = "";
    public $errorCode = "";
    public $details ="";
    public $bankidResult;
    public $orderRef;
    /**
     * Initialize bankid here so we crash early if something missconfigured. (maybe not good for unit test @todo refactor)
     *
     * @param UUID $id
     */
    public function __construct($id = null)
    {
        /*$this->bankidResult = new BankidResult();
        $this->bankidResult =$this->bankidResult;
*/
        $this->status = "waiting";
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
        $bankidResult = $this->bankid->Collect($this->bankidResult);
        $this->updateState($bankidResult);
        if ($this->bankidResult['status'] == 'complete') {
            $this->errorCode .= "<script>setTimeout(function(){window.location='/bankid/complete'},2000);</script>";
        }

    }

    /**
     * Update internal state with bankid response
     *
     * @param [type] $result
     * @return void
     */
  /*  private function updateState($bankidResult)
    {
        $this->status   = (isset($bankidResult['status']) && $this->status <> $bankidResult['status']) ? $bankidResult['status'] : $this->status;
        $this->errorCode = isset($bankidResult['errorCode']) ? $bankidResult['errorCode']:"";
        $this->details  = isset($bankidResult['details']) ? $bankidResult['details']:"";

        if(!empty($bankidResult['errorCode'])) $this->status = $bankidResult['errorCode'];

    }
*/
private function updateState(array $result)
{
    foreach ($result as $key => $val) {
        $key = trim($key);
        $this->$key = trim($val);
        Log::error("setting $key to $val");
    }
    $this->bankidResult = $result;
}
    /**
     * Start an auth
     *
     * @return void
     */
    public function authenticate()
    {
        $this->status = "collect";
        $this->bankidResult = $this->bankid->Authenticate($this->personalNumber);
        $this->updateState($this->bankidResult);

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
