<?php

namespace App\Http\Requests\Azaly;

use Illuminate\Foundation\Http\FormRequest;

class StoreAzalyFileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'string|required',
            'file' => 'required|mimes:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt|max:7000000',
        ];
    }
}
