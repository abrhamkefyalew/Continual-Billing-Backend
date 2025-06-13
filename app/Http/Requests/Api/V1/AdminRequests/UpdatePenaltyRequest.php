<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePenaltyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->penalty);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'penalty_type' => [
                'sometimes', 
                'string',
                Rule::in(\App\Models\Penalty::allowedTypes()),
            ],
            'percent_of_principal_price' => 'sometimes|numeric|between:1,99',
            'is_active' => ['sometimes', 'boolean',],
        ];
    }
}
