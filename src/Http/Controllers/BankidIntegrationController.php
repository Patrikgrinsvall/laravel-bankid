<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\BankidIntegration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\BankidIntegrationResource;
use App\Http\Resources\BankidIntegrationCollection;
use App\Http\Requests\BankidIntegrationStoreRequest;
use App\Http\Requests\BankidIntegrationUpdateRequest;

class BankidIntegrationController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', BankidIntegration::class);

        $search = $request->get('search', '');

        $bankidIntegrations = BankidIntegration::search($search)
            ->latest()
            ->paginate();

        return new BankidIntegrationCollection($bankidIntegrations);
    }

    /**
     * @param \App\Http\Requests\BankidIntegrationStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankidIntegrationStoreRequest $request)
    {
        $this->authorize('create', BankidIntegration::class);

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['layout'] = json_decode($validated['layout'], true);

        $validated['languages'] = json_decode($validated['languages'], true);

        $bankidIntegration = BankidIntegration::create($validated);

        return new BankidIntegrationResource($bankidIntegration);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, BankidIntegration $bankidIntegration)
    {
        $this->authorize('view', $bankidIntegration);

        return new BankidIntegrationResource($bankidIntegration);
    }

    /**
     * @param \App\Http\Requests\BankidIntegrationUpdateRequest $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function update(
        BankidIntegrationUpdateRequest $request,
        BankidIntegration $bankidIntegration
    ) {
        $this->authorize('update', $bankidIntegration);

        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['layout'] = json_decode($validated['layout'], true);

        $validated['languages'] = json_decode($validated['languages'], true);

        $bankidIntegration->update($validated);

        return new BankidIntegrationResource($bankidIntegration);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        BankidIntegration $bankidIntegration
    ) {
        $this->authorize('delete', $bankidIntegration);

        $bankidIntegration->delete();

        return response()->noContent();
    }
}
