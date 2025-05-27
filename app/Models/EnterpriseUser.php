<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Validators\Api\V1\PhoneNumberValidator;
use App\Traits\Api\V1\NonQueuedMediaConversions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Notifications\Api\V1\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EnterpriseUser extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, InteractsWithMedia, NonQueuedMediaConversions;

    protected $table = 'enterprise_users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enterprise_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'is_active',
        'is_admin',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // mutator function 
    // mutator functions are called automatically by laravel,       // so setPasswordAttribute is mutator function and called by laravel automatically
    // This method is a mutator that automatically processes the password value before saving it to the password attribute of the model.
    // if hashed password is sent = it will not be hashed.                     if UN-hashed password is sent = it will be hashed 
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = app('hash')->needsRehash($password) ? Hash::make($password) : $password;
        }
    }


    public function sendPasswordResetNotification($token)
    {
        $url = 'https://adiamat.com/enterprise-users/reset-password/?token='.$token; // modify this url // depending on your route

         $this->notify(new ResetPasswordNotification($url));
    }


    







    

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
                // 'phone_number' => 'unique:enterprise_users',
                'phone_number' => Rule::unique('enterprise_users')->ignore($this->id),
            ]);

            if ($validator->fails()) {
                throw new \Illuminate\Validation\ValidationException($validator);
            }
        }
    

        // Finally, the formatted or validated phone number is set back to the model's phone_number attribute
        $this->attributes['phone_number'] = $formattedPhoneNumber;
    }












    public function getNameAttribute()
    {
        return $this->getFullNameAttribute();
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }


    

    


    public function registerMediaConversions(?Media $media = null): void
    {
        $this->customizeMediaConversions();
    }

    // constants
    public const ENTERPRISE_USER_PROFILE_PICTURE = 'ENTERPRISE_USER_PROFILE_PICTURE';
}
