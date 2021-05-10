<?php
namespace Patrikgrinsvall\LaravelBankid\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Patrikgrinsvall\LaravelBankid\Bankid;

class BankidComponent extends Component
{
    private $bankid;                                                                        // external dependency.
    protected $rules = [ 'personalNumber' => 'required|min:12' ];            // validation rules
    public $message ='';                              // default message
    public $status = "bankid::messages.waiting";
    public $personalNumber = "";
    protected $listeners = [ 'personalNumberClick' => 'personalNumberClick' ];   // event for clicking input
    public $orderRef = "";
    public $hintCode = "";
    public $statusClass ="bg-green-600";
    /**
     * Initialize bankid here so we crash early if something missconfigured. (maybe not good for unit test @todo refactor)
     *
     * @param UUID $id
     */
    public function __construct($id = null)
    {
        $this->bankid = new Bankid();
        $this->bankid->check_configuration();
        $this->message = __('bankid::messages.EnterPersonalnumber');
        $this->personalNumber = __('bankid::messages.defaultPersonalnumber');
        parent::__construct($id = null);
    }

    /**
     * Event when personalNumberfield is clicked
     *
     * @return void
     */
    public function personalNumberClick()
    {
        if ($this->personalNumber === __('bankid::messages.defaultPersonalnumber')) {
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
        if (in_array($this->status, ['collect', 'pending', 'outstandingTransaction']) &&
            in_array($this->status, ['failed', 'error', 'alreadyInProgress']) === false)
        {
            $this->statusClass ="bg-gray-600";
            $result = $this->bankid->collect(['orderRef' => $this->orderRef]);

            $this->updateState($result);
            $this->message = (strpos(__( 'bankid::messages.' . $this->message), "RFA") !== false) ?
                __( __('bankid::messages.' . $this->message) . $this->message) :
                __('bankid::messages.' .$this->message);
            if ($result['status'] == 'complete') {
                redirect()->to(config('bankid.completeUrl'));
                $this->status = "complete";
            }
        } else {
            $this->statusClass ="bg-red-600";
            $this->status = 'failed';
            unset($this->orderRef);
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
