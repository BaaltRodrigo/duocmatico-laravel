<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSectionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response | bool
    {
        return Gate::authorize('update', $this->route('calendar'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sections' => ['sometimes', 'array', ],
            'sections.*' => [
                Rule::requiredIf($this->input('sections') !== []),
                'sometimes', 
                'integer', 
                'exists:academic_charge_subject,id'
            ],
        ];
    }

}
