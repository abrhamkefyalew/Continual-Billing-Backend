<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\Directive;
use Illuminate\Foundation\Http\FormRequest;

class StoreDirectiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', Directive::class);
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
            'directive_type' => ['required', 'string', 'unique:directives,directive_type'],
            'is_active' => ['sometimes', 'boolean',],
            'name' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
