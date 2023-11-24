<?php

namespace App\Http\Requests\ExamAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnswerAdminRequest extends FormRequest
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
            'question_id' => 'required|integer',
            'data' => 'required|array',
            'data.*.answer' => 'required|string',
            'data.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'data.*.score' => 'required|integer',
        ];
    }
}
