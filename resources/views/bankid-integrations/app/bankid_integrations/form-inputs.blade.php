@php $editing = isset($bankidIntegration) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="label"
            label="Label"
            :value="old('label', ($editing ? $bankidIntegration->label : ''))"
            maxlength="255"
            placeholder="Label"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea
            name="description"
            label="Description"
            maxlength="255"
            required
            >{{ old('description', ($editing ? $bankidIntegration->description :
            '')) }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.checkbox
            name="active"
            label="Active"
            :checked="old('active', ($editing ? $bankidIntegration->active : 0))"
        ></x-inputs.checkbox>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea name="pkcs" label="Pkcs" maxlength="255" required
            >{{ old('pkcs', ($editing ? $bankidIntegration->pkcs : ''))
            }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.password
            name="password"
            label="Password"
            maxlength="255"
            placeholder="Password"
        ></x-inputs.password>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="type" label="Type">
            @php $selected = old('type', ($editing ? $bankidIntegration->type : '')) @endphp
            <option value="pfx" {{ $selected == 'pfx' ? 'selected' : '' }} >Pfx</option>
            <option value="p12" {{ $selected == 'p12' ? 'selected' : '' }} >P12</option>
            <option value="pem" {{ $selected == 'pem' ? 'selected' : '' }} >Pem</option>
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="url_prefix"
            label="Url Prefix"
            :value="old('url_prefix', ($editing ? $bankidIntegration->url_prefix : ''))"
            maxlength="255"
            placeholder="Url Prefix"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="success_url"
            label="Success Url"
            :value="old('success_url', ($editing ? $bankidIntegration->success_url : ''))"
            maxlength="255"
            placeholder="Success Url"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="error_url"
            label="Error Url"
            :value="old('error_url', ($editing ? $bankidIntegration->error_url : ''))"
            maxlength="255"
            placeholder="Error Url"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="environment" label="Environment">
            @php $selected = old('environment', ($editing ? $bankidIntegration->environment : 'test')) @endphp
            <option value="test" {{ $selected == 'test' ? 'selected' : '' }} >Test</option>
            <option value="prod" {{ $selected == 'prod' ? 'selected' : '' }} >Prod</option>
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea name="layout" label="Layout" maxlength="255" required
            >{{ old('layout', ($editing ?
            json_encode($bankidIntegration->layout) : '')) }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea
            name="languages"
            label="Languages"
            maxlength="255"
            required
            >{{ old('languages', ($editing ?
            json_encode($bankidIntegration->languages) : ''))
            }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea
            name="extra_html"
            label="Extra Html"
            maxlength="255"
            required
            >{{ old('extra_html', ($editing ? $bankidIntegration->extra_html :
            '')) }}</x-inputs.textarea
        >
    </x-inputs.group>
</div>
