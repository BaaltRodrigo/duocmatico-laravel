<?php

namespace App\Http\Requests;

use App\Models\Calendar;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StoreCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response | bool
    {
        return Gate::authorize('create', Calendar::class);
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
            'is_public' => ['sometimes', 'boolean'],
            'options' => ['sometimes', 'nullable', 'json'],
            'academic_charge_id' => ['sometimes', 'nullable', 'exists:academic_charges,id'],
            'calendarable_type' => ['required', 'string', 'in:career,school'],
            'calendarable_id' => [
                'required',
                'integer',
                // Check if exists in careers or schools table
                function ($attribute, $value, $fail) {
                    if (!DB::table('careers')->where('id', $value)->exists() && !DB::table('schools')->where('id', $value)->exists()) {
                        $fail('The selected ' . $attribute . ' is invalid.');
                    }
                }
            ],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }
}
