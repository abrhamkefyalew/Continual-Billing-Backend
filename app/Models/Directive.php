<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Directive extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\DirectiveFactory> */
    use HasFactory;


    protected $table = 'directives';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'name',
        'is_active',
    ];



    public function assetUnits()
    {
        $this->hasMany(AssetUnit::class);
    }


    public function assetPools()
    {
        $this->hasMany(AssetPool::class);
    }




    // constants // constants for 'type' column in directives table
    public const DIRECTIVE_TYPE_DAY = 'DAY';
    public const DIRECTIVE_TYPE_WEEK = 'WEEK';
    public const DIRECTIVE_TYPE_14 = '14';
    public const DIRECTIVE_TYPE_15 = '15';
    public const DIRECTIVE_TYPE_MONTH = 'MONTH';
    public const DIRECTIVE_TYPE_QUARTER = 'QUARTER';
    public const DIRECTIVE_TYPE_SEMESTER = 'SEMESTER';
    public const DIRECTIVE_TYPE_YEAR = 'YEAR';





    /**
     * central list of Allowed directive types as a mutable property
     * 
     */
    public static $allowedTypes = [
        self::DIRECTIVE_TYPE_DAY,
        self::DIRECTIVE_TYPE_WEEK,
        self::DIRECTIVE_TYPE_14,
        self::DIRECTIVE_TYPE_15,
        self::DIRECTIVE_TYPE_MONTH,
        self::DIRECTIVE_TYPE_QUARTER,
        self::DIRECTIVE_TYPE_SEMESTER,
        self::DIRECTIVE_TYPE_YEAR,
    ];


    /**
     * central list of Allowed directive types as a method
     * 
     */
    // public static function allowedTypes(): array
    // {
    //     return [
    //         self::DIRECTIVE_TYPE_DAY,
    //         self::DIRECTIVE_TYPE_WEEK,
    //         self::DIRECTIVE_TYPE_14,
    //         self::DIRECTIVE_TYPE_15,
    //         self::DIRECTIVE_TYPE_MONTH,
    //         self::DIRECTIVE_TYPE_QUARTER,
    //         self::DIRECTIVE_TYPE_SEMESTER,
    //         self::DIRECTIVE_TYPE_YEAR,
    //     ];
    // }


    /**
     * Validation rules for model attributes.
     */
    //
    // NOT DRY
    //
    // public static $rules = [
    //     'type' => 'required|in:DAY,WEEK,14,15,MONTH,QUARTER,SEMESTER,YEAR',
    // ];

    // /**
    //  * Custom validation messages.
    //  */
    // public static $messages = [
    //     'type.in' => 'INVALID DIRECTIVE TYPE. Allowed values are: DAY, WEEK, 14, 15, MONTH, QUARTER, SEMESTER, YEAR.',
    // ];

    // /**
    //  * Override save() to validate input before saving.
    //  */
    // public function save(array $options = [])
    // {
    //     $validator = Validator::make($this->attributes, self::$rules, self::$messages);

    //     if ($validator->fails()) {
    //         throw new \Exception($validator->errors()->first());
    //     }

    //     parent::save($options);
    // }







    //
    // DRY
    //
    //
    /**
     * Get validation rules using Rule::in with constants
     */
    public static function getRules(): array
    {

        // return [
        //     'type' => [
        //         'required', 
        //         'string', 
        //         Rule::in([
        //             self::DIRECTIVE_TYPE_DAY, 
        //             self::DIRECTIVE_TYPE_WEEK, 
        //             self::DIRECTIVE_TYPE_MONTH, 
        //             self::DIRECTIVE_TYPE_14, 
        //             self::DIRECTIVE_TYPE_15, 
        //             self::DIRECTIVE_TYPE_QUARTER, 
        //             self::DIRECTIVE_TYPE_SEMESTER, 
        //             self::DIRECTIVE_TYPE_YEAR
        //         ]),
        //     ],
        // ];


        // Commented because
        // If self::$allowedTypes contains something like an int, null, boolean or unexpected type:
        //                          //  - It still gets converted to a string.  // - Laravel may accept it or fail silently.    // it's hard to debug
        // return [
        //     'type' => [
        //         'required', 'in:' . implode(',', self::$allowedTypes)
        //     ],
        // ];
    

        //
        return [
            'type' => [
                'required', 'string', Rule::in(self::$allowedTypes),
                // 'required', 'string', Rule::in(self::allowedTypes()),
            ],
        ];
    }


    /**
     * Custom validation messages
     */
    public static function getMessages(): array
    {
        return [
            'type.in' => 'INVALID DIRECTIVE TYPE. Allowed values are: ' . implode(', ', self::$allowedTypes) . '.',
        ];
    }


    /**
     * Validate model data before saving
     */
    public function save(array $options = [])
    {
        $validator = Validator::make($this->attributes, self::getRules(), self::getMessages());

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        parent::save($options);
    }

    

}
