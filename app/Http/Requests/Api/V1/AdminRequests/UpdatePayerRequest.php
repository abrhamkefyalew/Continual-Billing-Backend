<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->payer);
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
            // email should NOT be updated , Because it is being used for login currently
            // 'email' => [
            //     'sometimes', 'email', Rule::unique('payers')->ignore($this->payer->id),
            // ],

            'phone_number' => [
                'sometimes', 'numeric',  Rule::unique('payers')->ignore($this->payer->id),
            ],

            'is_active' => [
                'sometimes', 'boolean',
            ],

            // this column can ONLY be Set by the SUPER_ADMIN, 
            // if Payer is registering himself , he can NOT send the is_approved field
            'is_approved' => [
                'sometimes', 'boolean',
            ],



            // password should NOT be updated here
            // there is a different procedure for resetting password called=(forget-password and reset-password)
            // 'password' => [
            //     'sometimes', 'min:8', 'confirmed',
            // ],




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

            // MEDIA REMOVE
            
            // GOOD IDEA = ALL media should NOT be Cleared at once, media should be cleared by id, like one picture. so the whole collection should NOT be cleared using $clearMedia the whole collection
            

            // BAD IDEA = when doing remove image try to do it for specific collection
            'payer_profile_image_remove' => [
                'sometimes', 'boolean',
            ],

            //
            'payer_id_front_image_remove' => [
                'sometimes', 'boolean',
            ],
            'payer_id_back_image_remove' => [
                'sometimes', 'boolean',
            ],
        ];
    }
}
