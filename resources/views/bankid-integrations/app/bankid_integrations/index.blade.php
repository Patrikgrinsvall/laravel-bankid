<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.bankid_integrations.index_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <div class="mb-5 mt-4">
                    <div class="flex flex-wrap justify-between">
                        <div class="md:w-1/2">
                            <form>
                                <div class="flex items-center w-full">
                                    <x-inputs.text
                                        name="search"
                                        value="{{ $search ?? '' }}"
                                        placeholder="{{ __('crud.common.search') }}"
                                        autocomplete="off"
                                    ></x-inputs.text>

                                    <div class="ml-1">
                                        <button
                                            type="submit"
                                            class="button button-primary"
                                        >
                                            <i class="icon ion-md-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="md:w-1/2 text-right">
                            @can('create', App\Models\BankidIntegration::class)
                            <a
                                href="{{ route('bankid-integrations.create') }}"
                                class="button button-primary"
                            >
                                <i class="mr-1 icon ion-md-add"></i>
                                @lang('crud.common.create')
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="block w-full overflow-auto scrolling-touch">
                    <table class="w-full max-w-full mb-4 bg-transparent">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.label')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.description')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.active')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.pkcs')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.type')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.url_prefix')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.success_url')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.error_url')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.environment')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.layout')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.languages')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.bankid_integrations.inputs.extra_html')
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @forelse($bankidIntegrations as $bankidIntegration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->label ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->description ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->active ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->pkcs ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->type ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->url_prefix ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->success_url ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->error_url ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->environment ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <pre>
{{ json_encode($bankidIntegration->layout) ?? '-' }}</pre
                                    >
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <pre>
{{ json_encode($bankidIntegration->languages) ?? '-' }}</pre
                                    >
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $bankidIntegration->extra_html ?? '-' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-center"
                                    style="width: 134px;"
                                >
                                    <div
                                        role="group"
                                        aria-label="Row Actions"
                                        class="
                                            relative
                                            inline-flex
                                            align-middle
                                        "
                                    >
                                        @can('update', $bankidIntegration)
                                        <a
                                            href="{{ route('bankid-integrations.edit', $bankidIntegration) }}"
                                            class="mr-1"
                                        >
                                            <button
                                                type="button"
                                                class="button"
                                            >
                                                <i
                                                    class="icon ion-md-create"
                                                ></i>
                                            </button>
                                        </a>
                                        @endcan @can('view', $bankidIntegration)
                                        <a
                                            href="{{ route('bankid-integrations.show', $bankidIntegration) }}"
                                            class="mr-1"
                                        >
                                            <button
                                                type="button"
                                                class="button"
                                            >
                                                <i class="icon ion-md-eye"></i>
                                            </button>
                                        </a>
                                        @endcan @can('delete',
                                        $bankidIntegration)
                                        <form
                                            action="{{ route('bankid-integrations.destroy', $bankidIntegration) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                        >
                                            @csrf @method('DELETE')
                                            <button
                                                type="submit"
                                                class="button"
                                            >
                                                <i
                                                    class="
                                                        icon
                                                        ion-md-trash
                                                        text-red-600
                                                    "
                                                ></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="13">
                                    <div class="mt-10 px-4">
                                        {!! $bankidIntegrations->render() !!}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>
