<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnnouncementRequest extends FormRequest
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
            'announcement_id' => 'required|integer',
            'post' => 'array|required',
            'post.company_name' => 'required|string',
            'post.title' => 'required|string',
            
            'post.address' => 'required|integer',
        ];
    }
}
