<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Validators\Api\V1\PhoneNumberValidator;
use App\Traits\Api\V1\NonQueuedMediaConversions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Enterprise extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, NonQueuedMediaConversions;

    protected $table = 'enterprises';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'enterprise_description',
        'email',
        'phone_number',
        'is_active',
        'is_approved',
    ];









    

    // mutator function 
    // mutator functions are called automatically by laravel,
    // Define a mutator for the phone_number attribute
    public function setPhoneNumberAttribute($value)
    {
        $phoneNumberValidator = new PhoneNumberValidator();
    
        $formattedPhoneNumber = $phoneNumberValidator->formatAndValidatePhoneNumber($value);


        // check for uniqueness on the modified phone_number (i.e. $formattedPhoneNumber) after it has been processed through the formatting and validation steps
        // this condition is last to ensure that the uniqueness check is performed on the transformed and modified phone number (i.e. using the above if conditions) that will be stored in the database
        if ($this->where('phone_number', $formattedPhoneNumber)->exists()) {
            // Use Laravel's validation mechanism to return an error
            $validator = Validator::make(['phone_number' => $formattedPhoneNumber], [
                // 'phone_number' => 'unique:enterprises',
                'phone_number' => Rule::unique('enterprises')->ignore($this->id),
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        }
    

        // Finally, the formatted or validated phone number is set back to the model's phone_number attribute
        $this->attributes['phone_number'] = $formattedPhoneNumber;
    }











    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function enterpriseUsers()
    {
        return $this->hasMany(EnterpriseUser::class);
    }


    public function assetMains()
    {
        return $this->hasMany(AssetMain::class);
    }


    public function assetUnits()
    {
        return $this->hasMany(AssetUnit::class);
    }

    public function assetPools()
    {
        return $this->hasMany(AssetPool::class);
    }

    
    

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->customizeMediaConversions();
    }


    // do boot function , when enterprise is soft deleted , the enterprise user to be soft deleted
    // do boot function , when enterprise is restored , the enterprise user to be restored


    public const ENTERPRISE_PROFILE_PICTURE = 'ENTERPRISE_PROFILE_PICTURE';

}
