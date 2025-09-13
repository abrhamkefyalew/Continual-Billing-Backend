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
            // PENALTY ID is a shared value (its ID is index and is shared b/n multiple entries in the DB)
            //      If it is in use by other entities and we update it nevertheless, our DB integrity will become precarious.
            //              
            //
            // 1. Can be  updated - IF it NOT being used by any active AssetUnits/AssetMains/ORDERs in the system
            //      BUT = needs additional Logic in the Controller/service - to check this and other things
            //
            // - BUT - 
            //
            // 2. this SHOULD NOT this be updated - IF it is being used by active AssetUnits/AssetMains/ORDERs in the system
            //      //
            //      NEVER update this, B/C Penalty is a shared value (that is shared b/n multiple entries in the DB)
            //                              //
            //                              b/c the enterprises & payers who are actively using this Penalty trusts it to have its original value
            //                                                           if we update it then they will see payment amounts that they do NOT expect 
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
