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
            'education.*.date' => 'required|array',
            'education.*.date.start' => 'required|array',
            'education.*.date.start.year' => 'nullable|integer',
            'education.*.date.start.month' => 'nullable|integer',
            'education.*.date.end' => 'required|array',
            'education.*.date.end.year' => 'nullable|integer',
            'education.*.date.end.month' => 'nullable|integer',
            'education.*.date.present' => 'nullable|boolean',
            'education.*.degree' => 'nullable|string',
            'education.*.school' => 'nullable|string',
            'education.*.description' => 'nullable|string',
            'work_type' => 'required|string|min:3',
            'employment' => 'nullable|array',
            'employment.*.date' =>  'required|array',
            'employment.*.date.start' => 'required|array',
            'employment.*.date.start.year' => 'nullable|integer',
            'employment.*.date.start.month' => 'nullable|integer',
            'employment.*.date.end' => 'required|array',
            'employment.*.date.end.year' => 'nullable|integer',
            'employment.*.date.end.month' => 'nullable|integer',
            'employment.*.date.present' => 'nullable|boolean',
            'employment.*.title' => 'nullable|string|min:3',
            'employment.*.employer' => 'nullable|string|min:3',
            'employment.*.description' => 'nullable|string',
            'hide_salary' => 'required|boolean',
            'computer_skills'=> 'array',
            'driving_experience' => 'required|array',
            'driving_experience.availability_of_a_car' => 'required|boolean',
            'driving_experience.categories_of_driving' => 'required|array',
            'driving_experience.categories_of_driving.A' => 'required|boolean',
            'driving_experience.categories_of_driving.B' => 'required|boolean',
            'driving_experience.categories_of_driving.C' => 'required|boolean',
            'driving_experience.categories_of_driving.D' => 'required|boolean',
            'driving_experience.categories_of_driving.BE' => 'required|boolean',
            'driving_experience.categories_of_driving.CE' => 'required|boolean',
            'driving_experience.categories_of_driving.DE' => 'required|boolean',
            'additional_education' => 'nullable|array',
            'additional_education.*.date' => 'required|array',
            'additional_education.*.date.start' => 'required|array',
            'additional_education.*.date.start.year' => 'nullable|integer',
            'additional_education.*.date.start.month' => 'nullable|integer',
            'additional_education.*.date.end' => 'required|array',
            'additional_education.*.date.end.year' => 'nullable|integer',
            'additional_education.*.date.end.month' => 'nullable|integer',
            'additional_education.*.date.present' => 'boolean',
            'additional_education.*.name' => 'required|string|min:3',
            'additional_education.*.school' => 'required|string|min:3',
            'additional_education.*.description' => 'nullable|string',
        ];
    }
}
