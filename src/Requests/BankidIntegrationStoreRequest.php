<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankidIntegrationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'active' => ['required', 'boolean'],
            'pkcs' => ['required', 'max:255', 'string'],
            'password' => ['nullable'],
            'type' => ['required', 'in:pfx,p12,pem'],
            'url_prefix' => ['required', 'max:255', 'string'],
            'success_url' => ['required', 'max:255', 'string'],
            'error_url' => ['nullable', 'max:255', 'string'],
            'environment' => ['required', 'in:test,prod'],
            'layout' => ['required', 'max:255', 'json'],
            'languages' => ['required', 'max:255', 'json'],
            'extra_html' => ['required', 'max:255', 'string'],
        ];
    }
}
