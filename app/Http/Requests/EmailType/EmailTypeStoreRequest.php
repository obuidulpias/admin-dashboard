<?php

namespace App\Http\Requests\EmailType;

use Illuminate\Foundation\Http\FormRequest;

class EmailTypeStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'constant' => 'required|string|max:255|unique:email_types,constant',
        ];
    }
}
