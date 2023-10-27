<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCalendarRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'is_public' => ['sometimes', 'boolean'],
            'description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'options' => ['sometimes', 'nullable', 'max:255'],
        ];
    }
}
