<?php

namespace App\Http\Requests;

use App\Models\AcademicCharge;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAcademicChargeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', AcademicCharge::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'year' => ['required', 'integer'],
            'semester' => ['required', 'integer'],
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls'],
        ];
    }
}
