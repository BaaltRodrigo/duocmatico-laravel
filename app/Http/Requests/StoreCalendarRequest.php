<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'uuid' => ['sometimes', 'string', 'max:255'],
            'is_public' => ['sometimes', 'boolean'],
            'options' => ['sometimes', 'nullable', 'json'],
            'academic_charge_id' => ['sometimes', 'nullable', 'exists:academic_charges,id'],
            'calendarable_id' => ['required', 'exists:careers,id'],
            'calendarable_type' => ['required', 'string', 'in:career,school'],
        ];
    }

    // modify after validation passs
    public function passedValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
            'calendarable_type' => "App\Models\\" . ucfirst($this->calendarable_type),
        ]);
    }
}
