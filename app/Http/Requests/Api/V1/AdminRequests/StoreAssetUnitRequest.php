<?php

namespace App\Http\Requests\Api\V1\AdminRequests;

use App\Models\AssetUnit;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssetUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;

        // return $this->user()->can('create', AssetUnit::class);
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
            'payer_id' => 'required|integer|exists:payers,id',



            
            // multiple assetUnits can be sent at once 
            // i will put similar assetUnit_code in OrderController = for those multiple assetUnits that are sent at once
            'assetUnits' => 'required|array',

            'assetUnits.*' => 'sometimes',


            'assetUnits.*.asset_main_id' => 'required|integer|exists:asset_mains,id',
            
            'assetUnits.*.directive_id' => 'required|integer|exists:directives,id',
            'assetUnits.*.penalty_id' => 'required|integer|exists:penalties,id',

            'assetUnits.*.penalty_starts_after_days' => 'required|integer',
            'assetUnits.*.service_termination_penalty' => 'required|numeric|between:1,9999999.99',
            'assetUnits.*.price_principal' => 'required|numeric|between:1,9999999.99',
            'assetUnits.*.is_payment_by_term_end' => 'required|boolean',
            

            'assetUnits.*.start_date' => 'required|date|date_format:Y-m-d', 
            'assetUnits.*.end_date' => 'required|date|date_format:Y-m-d', 

            'assetUnits.*.payer_can_terminate' => 'required|boolean',
            

            'assetUnits.*.asset_unit_name' => 'sometimes|nullable|string',
            'assetUnits.*.assetUnit_description' => 'sometimes|nullable|string',

        ];
    }
}
