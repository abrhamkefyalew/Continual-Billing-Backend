<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnterpriseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', Enterprise::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Enterprise info
            'name' => [
                'required', 'string',
            ],
            'enterprise_description' => [
                'sometimes', 'nullable', 'string',
            ],
            'email' => [
                'required', 'email', Rule::unique('enterprises'),
            ],
            'phone_number' => [
                'required', 'numeric', Rule::unique('enterprises'),
            ],
            'is_active' => [
                'sometimes', 'boolean',
            ],

            // this will only be here , in the admin request // it will be removed from the request list for Everyone Else who is making a request
            'is_approved' => [
                'sometimes', 'boolean',
            ],

            'country' => [
                'sometimes', 'string',
            ],
            'city' => [
                'sometimes', 'string',
            ],

            'enterprise_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],
            // since it is Storing Enterprise for the first time there is no need to remove any image // so we do NOT need remove_image
            // and also when removing image, we should also provide the collection to remove only specific collection like, ENTERPRISE_PROFILE_IMAGE 
            // AND for remove send it just like the following     // the request key value + remove //
            // 'enterprise_profile_image_remove' => [
            //     'sometimes', 'boolean',
            // ],



            // EnterpriseUser info
            'user_first_name' => [
                'required', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'user_last_name' => [
                'required', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'user_email' => [
                'required', 'email', 'unique:enterprise_users,email',
            ],
            'user_phone_number' => [
                'required', 'numeric', 'unique:enterprise_users,phone_number',
            ],
            'user_is_active' => [
                'sometimes', 'boolean',
            ],
            // Because it is the first time the enterprise is being registered by admin, the enterpriseUser created with it must be EnterpriseAdmin
            // 'user_is_admin' => [
            //     'sometimes', 'nullable', 'boolean',
            // ],


            // we are using OTP , so password is commented until further notice
            'user_password' => [
                'required', 'min:8', 'confirmed',
            ],



            'user_country' => [
                'sometimes', 'string',
            ],
            'user_city' => [
                'sometimes', 'string',
            ],
            
            'enterprise_user_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],
            // since it is Storing Enterprise User for the first time there is no need to remove any image // so we do NOT need remove_image
            // and also when removing image, we should also provide the collection to remove only specific collection like, ENTERPRISE_USER_PROFILE_PICTURE or DRIVER_LICENSE_PICTURE, 
            // AND for remove send it just like the following     // the request key value + remove //
            // 'enterprise_user_profile_image_remove' => [
            //     'sometimes', 'boolean',
            // ],


        ];
    }
}
