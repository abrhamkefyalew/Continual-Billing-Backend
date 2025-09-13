<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\Penalty;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePenaltyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', Penalty::class);
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
                'required', 
                'string',
                Rule::in(\App\Models\Penalty::allowedTypes()),
            ],
            'percent_of_principal_price' => 'required|numeric|between:1,99',
            'is_active' => ['sometimes', 'boolean',],
        ];
    }
}
