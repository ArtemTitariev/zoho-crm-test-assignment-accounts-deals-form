<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDealAndAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_name' => 'required|string|min:2|max:50',
            'account_website' => 'nullable|url',
            'account_phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'deal_name' => 'required|string|min:2|max:150',
            'deal_stage' => 'required|string|min:2|max:50',
        ];
    }
}
