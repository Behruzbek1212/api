<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TraficRequest extends FormRequest
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
            'slug' => ['numeric'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['string'],
            'price' => ['numeric'],
            'title' => ['string'],
            'description' => ['string'],
            'top_day' => ['numeric'],
            'count_rise' => ['numeric'],
            'vip_day' => ['numeric'],
            'type' => ['numeric'],
        ];
    }
}
