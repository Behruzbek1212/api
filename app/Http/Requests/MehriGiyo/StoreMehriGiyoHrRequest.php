<?php

namespace App\Http\Requests\MehriGiyo;

use Illuminate\Foundation\Http\FormRequest;

class StoreMehriGiyoHrRequest extends FormRequest
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
            'message_id' => 'required',
            'token' => 'required|string',
            'data' => 'required|array'
        ];
    }
}