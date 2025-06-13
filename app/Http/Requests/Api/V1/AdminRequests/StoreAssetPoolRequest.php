<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\AssetPool;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetPoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        return $this->user()->can('create', AssetPool::class);
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
        ];
    }
}
