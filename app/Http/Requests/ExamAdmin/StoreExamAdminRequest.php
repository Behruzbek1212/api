<?php

namespace App\Http\Requests\ExamAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamAdminRequest extends FormRequest
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
            'name' => 'required|string',
            'title' => 'required|string',
            'key' => 'required|string',
            'attemps_count' => 'nullable|integer',
            'duration' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'questions_count' => 'nullable|integer',
            'max_ball' => 'nullable|integer'
        ];
    }
}
