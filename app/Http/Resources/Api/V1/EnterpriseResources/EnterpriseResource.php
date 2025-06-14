<?php

namespace App\Http\Resources\Api\V1\EnterpriseResources;

use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Traits\Api\V1\GetMedia;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\AddressResources\AddressResource;
use App\Http\Resources\Api\V1\AssetMainResources\AssetMainResource;
use App\Http\Resources\Api\V1\EnterpriseUserResources\EnterpriseUserResource;

class EnterpriseResource extends JsonResource
{
    use GetMedia;
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'enterprise_description' => $this->enterprise_description,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'is_active' => $this->is_active,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'enterprise_profile_image_path' => $this->getOptimizedImagePath(Enterprise::ENTERPRISE_PROFILE_PICTURE),
            'address' => AddressResource::make($this->whenLoaded('address')),

            'enterprise_assetMains' => AssetMainResource::collection($this->whenLoaded('assetMains')),

            'enterprise_enterpriseUsers' => EnterpriseUserResource::collection($this->whenLoaded('enterpriseUsers', function () {
                return $this->enterpriseUsers->load('address', 'media');
            })),
        ];
    }
}
