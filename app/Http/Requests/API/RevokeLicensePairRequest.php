<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RevokeLicensePairRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_key' => 'required|string',
            'domain' => 'nullable|string',
        ];
    }
}
