<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BankidIntegration;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankidRequestsResource;
use App\Http\Resources\BankidRequestsCollection;

class BankidIntegrationAllBankidRequestsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        BankidIntegration $bankidIntegration
    ) {
        $this->authorize('view', $bankidIntegration);

        $search = $request->get('search', '');

        $allBankidRequests = $bankidIntegration
            ->allBankidRequests()
            ->search($search)
            ->latest()
            ->paginate();

        return new BankidRequestsCollection($allBankidRequests);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        BankidIntegration $bankidIntegration
    ) {
        $this->authorize('create', BankidRequests::class);

        $validated = $request->validate([
            'response' => ['required', 'string'],
            'orderRef' => ['required', 'max:255', 'string'],
            'status' => ['required', 'max:255', 'string'],
        ]);

        $bankidRequests = $bankidIntegration
            ->allBankidRequests()
            ->create($validated);

        return new BankidRequestsResource($bankidRequests);
    }
}
