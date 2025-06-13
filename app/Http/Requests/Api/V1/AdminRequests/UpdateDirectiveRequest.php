<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->directive);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'directive_type' => [
                'sometimes', 
                'string', 
                Rule::unique('directives')->ignore($this->directive->id),
            ],  // should NOT be nullable // since directive_type can NOT be updated to be null

            'is_active' => ['sometimes', 'boolean',],

            'name' => ['sometimes', 'nullable', 'string'],  // should be nullable // since name can be updated to be empty or null
        ];
    }
}
