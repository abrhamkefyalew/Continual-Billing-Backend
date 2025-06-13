<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\EnterpriseUser;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnterpriseUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', EnterpriseUser::class);
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
            'enterprise_id' => 'required|integer|exists:enterprises,id',
            'first_name' => [
                'required', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'last_name' => [
                'required', 'string', 'regex:/^\S*$/u', 'alpha',
            ],
            'email' => [
                'required', 'email', Rule::unique('enterprise_users'),
            ],
            'phone_number' => [
                'required', 'numeric', Rule::unique('enterprise_users'),
            ],
            'is_active' => [
                'sometimes', 'boolean',
            ],
            
            'is_admin' => [
                'sometimes', 'boolean',
            ],


            // we are using OTP , so password is commented until further notice
            'password' => [
                'required', 'min:8', 'confirmed',
            ],



            'country' => [
                'sometimes', 'string',
            ],
            'city' => [
                'sometimes', 'string',
            ],
            
            'enterprise_user_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],
            // since it is Storing Enterprise User for the first time there is no need to remove any image // so we do NOT need remove_image
            // and also when removing image, we should also provide the collection to remove only specific collection like, ENTERPRISE_USER_PROFILE_PICTURE
            // AND for remove send it just like the following     // the request key + remove //
            // 'enterprise_user_profile_image_remove' => [
            //     'sometimes', 'boolean',
            // ],
        ];
    }
}
