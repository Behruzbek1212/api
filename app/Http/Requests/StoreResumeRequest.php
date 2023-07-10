<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeRequest extends FormRequest
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
            'about' => 'nullable|string|min:10',
            'links'=> 'array',
            'salary' => 'required',
            'salary.amount' => 'string|min:3',
            'salary.currency' => 'string|min:1',
            'salary.agreement' => 'boolean',
            'skills' => 'array',
            'sphere' => 'required|string|min:2',
            'location'=> 'required|string|min:3',
            'position' => 'required|string|min:5',
            'status' => 'required|string',
            'education' => 'nullable|array',
            'education.*.date' => 'nullable|array',
            'education.*.date.start' => 'nullable|array',
            'education.*.date.start.year' => 'nullable|integer',
            'education.*.date.start.month' => 'nullable|integer',
            'education.*.date.end' => 'nullable|array',
            'education.*.date.end.year' => 'nullable|integer',
            'education.*.date.end.month' => 'nullable|integer',
            'education.*.date.present' => 'nullable|boolean',
            'education.*.degree' => 'nullable|string',
            'education.*.school' => 'nullable|string',
            'education.*.description' => 'nullable|string',
            'work_type' => 'required|string|min:3',
            'employment' => 'nullable|array',
            'employment.*.date' => 'nullable|array',
            'employment.*.date.start' => 'nullable|array',
            'employment.*.date.start.year' => 'nullable|integer',
            'employment.*.date.start.month' => 'nullable|integer',
            'employment.*.date.end' => 'nullable|array',
            'employment.*.date.end.year' => 'nullable|integer',
            'employment.*.date.end.month' => 'nullable|integer',
            'education.*.title' => 'nullable|string|min:3',
            'education.*.employer' => 'nullable|string|min:3',
            'education.*.description' => 'nullable|string',
            'hide_salary' => 'boolean',
            'computer_skills'=> 'array',
            'driving_experience' => 'array',
            'driving_experience.availability_of_a_car' => 'boolean',
            'additional_education' => 'array'
        ];
    }
}
