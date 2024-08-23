<?php

namespace App\Http\Requests\RailWay;

use Illuminate\Foundation\Http\FormRequest;

class StoreRailWayFileRequest extends FormRequest
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
            'file' => 'required|mimes:jpg,jpeg,png,pdf,csv,docx,txt|max:7000000',
        ];
    }
}
