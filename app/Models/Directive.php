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
        'is_active',
        'name',
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
     * central list of Allowed Directive Types as a mutable property
     * 
     */
    // public static $allowedTypes = [
    //     self::DIRECTIVE_TYPE_DAY,
    //     self::DIRECTIVE_TYPE_WEEK,
    //     self::DIRECTIVE_TYPE_14,
    //     self::DIRECTIVE_TYPE_15,
    //     self::DIRECTIVE_TYPE_MONTH,
    //     self::DIRECTIVE_TYPE_QUARTER,
    //     self::DIRECTIVE_TYPE_SEMESTER,
    //     self::DIRECTIVE_TYPE_YEAR,
    // ];


    /**
     * central list of Allowed Directive types as a method
     * 
     */
    public static function allowedTypes(): array
    {
        return [
            self::DIRECTIVE_TYPE_DAY,
            self::DIRECTIVE_TYPE_WEEK,
            self::DIRECTIVE_TYPE_14,
            self::DIRECTIVE_TYPE_15,
            self::DIRECTIVE_TYPE_MONTH,
            self::DIRECTIVE_TYPE_QUARTER,
            self::DIRECTIVE_TYPE_SEMESTER,
            self::DIRECTIVE_TYPE_YEAR,
        ];
    }


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
        // If (self::allowedTypes() - or - self::$allowedTypes) - contains something like an int, null, boolean or unexpected type:
        //                          //  - It still gets converted to a string.  // - Laravel may accept it or fail silently.    // it's hard to debug
        // return [
        //     'type' => [
        //         // 'required', 'in:' . implode(',', self::$allowedTypes)
        //         'required', 'in:' . implode(',', self::allowedTypes())
        //     ],
        // ];
    

        //
        return [
            'type' => [
                // 'required', 'string', Rule::in(self::$allowedTypes),
                'required', 'string', Rule::in(self::allowedTypes()),
            ],
        ];
    }


    /**
     * Custom validation messages
     */
    public static function getMessages(): array
    {
        return [
            'type.in' => 'INVALID DIRECTIVE TYPE. Allowed values are: ' . implode(', ', self::allowedTypes()) . '.',  // this is just returning ERROR message,  SO using = [ implode(', ', self::allowedTypes()) ] here is NOT a Problem.
        ];
    }


    /**
     * Validate model data before doing WRITE Operation
     * 
     * Override save() to validate input before saving.
     * 
     *  = > NOT recommended BECAUSE
     *                      //
     *                      - ONLY works if save() is called directly form Controller or other code parts
     *                      - will NOT work for other write functions -> i.e. create(), update(), updateOrCreate(), fill()
     * 
     */
    // public function save(array $options = [])
    // {
    //     $validator = Validator::make($this->attributes, self::getRules(), self::getMessages());

    //     if ($validator->fails()) {
    //         throw new \Exception($validator->errors()->first());
    //     }

    //     parent::save($options);
    // }



    /**
     * Validate model data before doing WRITE Operation
     * 
     * The "booting" method of the model.
     *
     * Registers a saving event that validates model attributes before persisting.
     * This ensures that only a validated data is written to Database during  - > save() / fill()->save() / create() / update() / updateOrCreate() operations.
     * 
     * But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely 
     * 
     * 
     *  = > RECOMMENDED BECAUSE
     *                      //
     *                      - Triggers Validation and Writing to DB during - > i.e. save(), create(), update(), updateOrCreate(), fill() operations.
     *                      - But still NOT work for insert() and upsert() - Because these are Query Builder-level operations, bypassing Eloquent models entirely, - so no events (and no validation) are triggered.
     * 
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $validator = Validator::make($model->attributesToArray(), self::getRules(), self::getMessages());

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
        });
    }

    

}
