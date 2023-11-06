<?php

namespace App\Http\Requests\ExamAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionAdminRequest extends FormRequest
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
            'exam_id'=> 'required|integer',
            'question' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov,wmv|max:20480',
            'position' => 'nullable|integer'
        ];
    }
}
