<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
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
            'data' => 'required|array',
            'data.company_name' => 'required|string',
            'data.title' => 'required|string',
            'data.salary' => 'array|required',
            'data.salary.amount'=> 'required',
            'data.salary.agreement' => 'required|boolean',
            'data.address' => 'required|integer',
        ];
    }
}
