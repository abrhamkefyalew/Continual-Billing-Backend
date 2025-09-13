<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\AssetMain;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetMainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', AssetMain::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
            'enterprise_id' => 'required|integer|exists:enterprises,id',

            'asset_name' => [
                'required', 'string',
            ],
            'asset_description' => [
                'sometimes', 'nullable', 'string',
            ],

            'is_active' => [
                'sometimes', 'boolean',
            ],

            'type' => [
                'required', 'string', Rule::in(\App\Models\AssetMain::$allowedTypes),
            ],

            

            'country' => [
                'sometimes', 'string',
            ],
            'city' => [
                'sometimes', 'string',
            ],


            'asset_profile_image' => [
                'required',
                'image',
                'max:3072',
            ],

        ];
    }
}
