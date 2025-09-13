<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnterpriseUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->enterpriseUser);
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
            // 'enterprise_id' => 'sometimes|integer|exists:enterprises,id',  // should never UPDATE this, b/c if updated:- the changed enterprise_id will own all the changelogs of the updated enterprize user
            //

            'first_name' => [
                'sometimes', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'last_name' => [
                'sometimes', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            // email should NOT be updated , Because it is being used for login currently
            // 'email' => [
            //     'sometimes', 'email', Rule::unique('enterprise_users')->ignore($this->enterpriseUser->id),
            // ],
            'phone_number' => [
                'sometimes', 'numeric', Rule::unique('enterprise_users')->ignore($this->enterpriseUser->id),
            ],

            // there should be separate endpoint to update this , // if 0, the user will be automatically logged out
            // 'is_active' => [
            //     'sometimes', 'boolean',
            // ],
            
            'is_admin' => [
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
            'enterprise_user_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],

            // MEDIA REMOVE
            
            // GOOD IDEA = ALL media should NOT be Cleared at once, media should be cleared by id, like one picture. so the whole collection should NOT be cleared using $clearMedia the whole collection
            

            // BAD IDEA = when doing remove image try to do it for specific collection
            'enterprise_user_profile_image_remove' => [
                'sometimes', 'boolean',
            ],
        ];
    }
}
