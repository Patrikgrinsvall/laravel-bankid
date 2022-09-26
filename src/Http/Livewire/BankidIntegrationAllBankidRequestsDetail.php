<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BankidRequests;
use App\Models\BankidIntegration;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BankidIntegrationAllBankidRequestsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public BankidIntegration $bankidIntegration;
    public BankidRequests $bankidRequests;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New BankidRequests';

    protected $rules = [
        'bankidRequests.response' => ['required', 'string'],
        'bankidRequests.orderRef' => ['required', 'max:255', 'string'],
        'bankidRequests.status' => ['required', 'max:255', 'string'],
    ];

    public function mount(BankidIntegration $bankidIntegration)
    {
        $this->bankidIntegration = $bankidIntegration;
        $this->resetBankidRequestsData();
    }

    public function resetBankidRequestsData()
    {
        $this->bankidRequests = new BankidRequests();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newBankidRequests()
    {
        $this->editing = false;
        $this->modalTitle = trans(
            'crud.bankid_integration_all_bankid_requests.new_title'
        );
        $this->resetBankidRequestsData();

        $this->showModal();
    }

    public function editBankidRequests(BankidRequests $bankidRequests)
    {
        $this->editing = true;
        $this->modalTitle = trans(
            'crud.bankid_integration_all_bankid_requests.edit_title'
        );
        $this->bankidRequests = $bankidRequests;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal()
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal()
    {
        $this->showingModal = false;
    }

    public function save()
    {
        $this->validate();

        if (!$this->bankidRequests->bankid_integration_id) {
            $this->authorize('create', BankidRequests::class);

            $this->bankidRequests->bankid_integration_id =
                $this->bankidIntegration->id;
        } else {
            $this->authorize('update', $this->bankidRequests);
        }

        $this->bankidRequests->save();

        $this->hideModal();
    }

    public function destroySelected()
    {
        $this->authorize('delete-any', BankidRequests::class);

        BankidRequests::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetBankidRequestsData();
    }

    public function toggleFullSelection()
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach (
            $this->bankidIntegration->allBankidRequests
            as $bankidRequests
        ) {
            array_push($this->selected, $bankidRequests->id);
        }
    }

    public function render()
    {
        return view('livewire.bankid-integration-all-bankid-requests-detail', [
            'allBankidRequests' => $this->bankidIntegration
                ->allBankidRequests()
                ->paginate(20),
        ]);
    }
}
