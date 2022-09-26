<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BankidRequests;
use App\Http\Controllers\Controller;
use App\Http\Resources\BankidRequestsResource;
use App\Http\Resources\BankidRequestsCollection;
use App\Http\Requests\BankidRequestsStoreRequest;
use App\Http\Requests\BankidRequestsUpdateRequest;

class BankidRequestsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', BankidRequests::class);

        $search = $request->get('search', '');

        $allBankidRequests = BankidRequests::search($search)
            ->latest()
            ->paginate();

        return new BankidRequestsCollection($allBankidRequests);
    }

    /**
     * @param \App\Http\Requests\BankidRequestsStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankidRequestsStoreRequest $request)
    {
        $this->authorize('create', BankidRequests::class);

        $validated = $request->validated();

        $bankidRequests = BankidRequests::create($validated);

        return new BankidRequestsResource($bankidRequests);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidRequests $bankidRequests
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, BankidRequests $bankidRequests)
    {
        $this->authorize('view', $bankidRequests);

        return new BankidRequestsResource($bankidRequests);
    }

    /**
     * @param \App\Http\Requests\BankidRequestsUpdateRequest $request
     * @param \App\Models\BankidRequests $bankidRequests
     * @return \Illuminate\Http\Response
     */
    public function update(
        BankidRequestsUpdateRequest $request,
        BankidRequests $bankidRequests
    ) {
        $this->authorize('update', $bankidRequests);

        $validated = $request->validated();

        $bankidRequests->update($validated);

        return new BankidRequestsResource($bankidRequests);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidRequests $bankidRequests
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, BankidRequests $bankidRequests)
    {
        $this->authorize('delete', $bankidRequests);

        $bankidRequests->delete();

        return response()->noContent();
    }
}
