<?php

namespace App\Http\Requests\Api\V1\AuthRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterPayerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'first_name' => [
                'sometimes', 'nullable', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'last_name' => [
                'sometimes', 'nullable', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'email' => [
                'required', 'email', Rule::unique('payers'),
            ],
            'password' => [
                'required', 'min:8', 'confirmed',
            ],
            'phone_number' => [
                'required', 'numeric',  Rule::unique('payers'),
            ],


            'country' => [
                'sometimes', 'string',
            ],
            'city' => [
                'sometimes', 'string',
            ],


            // MEDIA ADD
            'payer_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],

            //
            'payer_id_front_image' => [
                'sometimes',       // this should be required abrham check
                'image',
                'max:13072',        // decrease this size
            ],
            'payer_id_back_image' => [
                'sometimes',    // this should be required abrham check
                'image',
                'max:13072',     // decrease this size
            ],
            
        ];
    }
}
