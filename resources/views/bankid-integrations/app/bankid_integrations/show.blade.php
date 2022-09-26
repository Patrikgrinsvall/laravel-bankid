<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.bankid_integrations.show_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a
                        href="{{ route('bankid-integrations.index') }}"
                        class="mr-4"
                        ><i class="mr-1 icon ion-md-arrow-back"></i
                    ></a>
                </x-slot>

                <div class="mt-4 px-4">
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.label')
                        </h5>
                        <span>{{ $bankidIntegration->label ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.description')
                        </h5>
                        <span
                            >{{ $bankidIntegration->description ?? '-' }}</span
                        >
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.active')
                        </h5>
                        <span>{{ $bankidIntegration->active ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.pkcs')
                        </h5>
                        <span>{{ $bankidIntegration->pkcs ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.type')
                        </h5>
                        <span>{{ $bankidIntegration->type ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.url_prefix')
                        </h5>
                        <span>{{ $bankidIntegration->url_prefix ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.success_url')
                        </h5>
                        <span
                            >{{ $bankidIntegration->success_url ?? '-' }}</span
                        >
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.error_url')
                        </h5>
                        <span>{{ $bankidIntegration->error_url ?? '-' }}</span>
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.environment')
                        </h5>
                        <span
                            >{{ $bankidIntegration->environment ?? '-' }}</span
                        >
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.layout')
                        </h5>
                        <pre>
{{ json_encode($bankidIntegration->layout) ?? '-' }}</pre
                        >
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.languages')
                        </h5>
                        <pre>
{{ json_encode($bankidIntegration->languages) ?? '-' }}</pre
                        >
                    </div>
                    <div class="mb-4">
                        <h5 class="font-medium text-gray-700">
                            @lang('crud.bankid_integrations.inputs.extra_html')
                        </h5>
                        <span>{{ $bankidIntegration->extra_html ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-10">
                    <a
                        href="{{ route('bankid-integrations.index') }}"
                        class="button"
                    >
                        <i class="mr-1 icon ion-md-return-left"></i>
                        @lang('crud.common.back')
                    </a>

                    @can('create', App\Models\BankidIntegration::class)
                    <a
                        href="{{ route('bankid-integrations.create') }}"
                        class="button"
                    >
                        <i class="mr-1 icon ion-md-add"></i>
                        @lang('crud.common.create')
                    </a>
                    @endcan
                </div>
            </x-partials.card>

            @can('view-any', App\Models\BankidRequests::class)
            <x-partials.card class="mt-5">
                <x-slot name="title"> All Bankid Requests </x-slot>

                <livewire:bankid-integration-all-bankid-requests-detail
                    :bankidIntegration="$bankidIntegration"
                />
            </x-partials.card>
            @endcan
        </div>
    </div>
</x-app-layout>
