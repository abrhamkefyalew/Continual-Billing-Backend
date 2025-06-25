<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePenaltyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('update', $this->penalty);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // abrham check/remember
            //
            // Can be  updated - IF it NOT being used by any active AssetUnits/AssetMains/ORDERs in the system
            //      BUT = needs additional Logic in the Controller/service - to check this and other things
            //
            // - BUT - 
            //
            // SHOULD this be updated - IF it is being used by active AssetUnits/AssetMains/ORDERs in the system ?
            //      my current thought NO
            //      but i could find ways to address issue  - or -  handle it in way so that i can update it even if it is being actively being use
            //
            //
            // 'penalty_type' => [
            //     'sometimes', 
            //     'string',
            //     Rule::in(\App\Models\Penalty::allowedTypes()),
            // ],

            'percent_of_principal_price' => 'sometimes|numeric|between:1,99',
            'is_active' => ['sometimes', 'boolean',],
        ];
    }
}
