<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeRequest extends FormRequest
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
            'data.about' => 'nullable|string|min:10',
            'data.links'=> 'required|array',
            'links.other' => 'nullable|string',
            'links.gitHub' => 'nullable|string',
            'links.behance' => 'nullable|string',
            'links.linkedin' => 'nullable|string',
            'links.telegram' => 'nullable|string',
            'links.whatsapp' => 'nullable|string',
            'links.instagram' => 'nullable|string',
            'data.salary' => 'required|array',
            'data.salary.amount' => 'string|min:3',
            'data.salary.currency' => 'string|min:1',
            'data.salary.agreement' => 'boolean',
            'data.skills' => 'array',
            'data.sphere' => 'required|string|min:2',
            'data.location'=> 'required|string|min:3',
            'data.position' => 'required|string|min:5',
            'data.status' => 'required|string',
            'data.education' => 'nullable|array',
            'data.education.*.date' => 'required|array',
            'data.education.*.date.start' => 'required|array',
            'data.education.*.date.start.year' => 'nullable|integer',
            'data.education.*.date.start.month' => 'nullable|integer',
            'data.education.*.date.end' => 'required|array',
            'data.education.*.date.end.year' => 'nullable|integer',
            'data.education.*.date.end.month' => 'nullable|integer',
            'data.education.*.date.present' => 'nullable|boolean',
            'data.education.*.degree' => 'nullable|string',
            'data.education.*.school' => 'nullable|string',
            'data.education.*.description' => 'nullable|string',
            'data.work_type' => 'required|string|min:3',
            'data.employment' => 'nullable|array',
            'data.employment.*.date' =>  'required|array',
            'data.employment.*.date.start' => 'required|array',
            'data.employment.*.date.start.year' => 'nullable|integer',
            'data.employment.*.date.start.month' => 'nullable|integer',
            'data.employment.*.date.end' => 'required|array',
            'data.employment.*.date.end.year' => 'nullable|integer',
            'data.employment.*.date.end.month' => 'nullable|integer',
            'data.employment.*.date.present' => 'nullable|boolean',
            'data.employment.*.title' => 'nullable|string|min:3',
            'data.employment.*.employer' => 'nullable|string|min:3',
            'data.employment.*.description' => 'nullable|string',
            'data.hide_salary' => 'required|boolean',
            'data.computer_skills'=> 'array',
            'data.driving_experience' => 'required|array',
            'data.driving_experience.availability_of_a_car' => 'required|boolean',
            'data.driving_experience.categories_of_driving' => 'required|array',
            'data.driving_experience.categories_of_driving.A' => 'required|boolean',
            'data.driving_experience.categories_of_driving.B' => 'required|boolean',
            'data.driving_experience.categories_of_driving.C' => 'required|boolean',
            'data.driving_experience.categories_of_driving.D' => 'required|boolean',
            'data.driving_experience.categories_of_driving.BE' => 'required|boolean',
            'data.driving_experience.categories_of_driving.CE' => 'required|boolean',
            'data.driving_experience.categories_of_driving.DE' => 'required|boolean',
            'data.additional_education' => 'nullable|array',
            'data.additional_education.*.date' => 'required|array',
            'data.additional_education.*.date.start' => 'required|array',
            'data.additional_education.*.date.start.year' => 'nullable|integer',
            'data.additional_education.*.date.start.month' => 'nullable|integer',
            'data.additional_education.*.date.end' => 'required|array',
            'data.additional_education.*.date.end.year' => 'nullable|integer',
            'data.additional_education.*.date.end.month' => 'nullable|integer',
            'data.additional_education.*.date.present' => 'boolean',
            'data.additional_education.*.name' => 'nullable|string',
            'data.additional_education.*.school' => 'nullable|string',
            'data.additional_education.*.description' => 'nullable|string',
        ];
    }
}
