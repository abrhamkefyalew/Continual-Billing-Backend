<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetMainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->assetMain);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'enterprise_id' => 'sometimes|nullable|integer|exists:enterprises,id',

            'asset_name' => [
                'sometimes', 'string',
            ],
            'asset_description' => [
                'sometimes', 'nullable', 'string',
            ],

            'is_active' => [
                'sometimes', 'boolean',
            ],

            'type' => [
                'sometimes', 'string', Rule::in(\App\Models\AssetMain::$allowedTypes),
            ],

            

            'country' => [
                'sometimes', 'string',
            ],
            'city' => [
                'sometimes', 'string',
            ],


            'asset_profile_image' => [
                'sometimes',
                'image',
                'max:3072',
            ],

            //

            'asset_profile_image_remove' => [
                'sometimes', 'boolean',
            ],
            
        ];
    }
}
