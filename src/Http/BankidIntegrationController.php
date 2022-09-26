<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankidIntegration;
use Illuminate\Support\Facades\Hash;
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
            ->paginate(5)
            ->withQueryString();

        return view(
            'app.bankid_integrations.index',
            compact('bankidIntegrations', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', BankidIntegration::class);

        return view('app.bankid_integrations.create');
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

        return redirect()
            ->route('bankid-integrations.edit', $bankidIntegration)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, BankidIntegration $bankidIntegration)
    {
        $this->authorize('view', $bankidIntegration);

        return view(
            'app.bankid_integrations.show',
            compact('bankidIntegration')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\BankidIntegration $bankidIntegration
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, BankidIntegration $bankidIntegration)
    {
        $this->authorize('update', $bankidIntegration);

        return view(
            'app.bankid_integrations.edit',
            compact('bankidIntegration')
        );
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

        return redirect()
            ->route('bankid-integrations.edit', $bankidIntegration)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('bankid-integrations.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
