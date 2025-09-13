<?php

namespace App\Http\Resources\Api\V1\EnterpriseUserResources;

use Illuminate\Http\Request;
use App\Traits\Api\V1\GetMedia;
use App\Models\EnterpriseUser;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\AddressResources\AddressResource;
use App\Http\Resources\Api\V1\EnterpriseResources\EnterpriseResource;

class EnterpriseUserResource extends JsonResource
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
            'enterprise_id' => $this->enterprise_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'is_active' => $this->is_active,
            'is_admin' => $this->is_admin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'enterprise_user_profile_image_path' => $this->getOptimizedImagePath(EnterpriseUser::ENTERPRISE_USER_PROFILE_PICTURE),
            
            'address' => AddressResource::make($this->whenLoaded('address')),

            'enterprise' => EnterpriseResource::make($this->whenLoaded('enterprise', function () {
                return $this->enterprise->load('address', 'media');
            })),
        ];
    }
}
