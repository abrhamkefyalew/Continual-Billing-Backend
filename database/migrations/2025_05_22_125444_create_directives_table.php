<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('directives', function (Blueprint $table) {
            $table->id();

            $table->string('directive_type')->unique();   // this column must always be constant value from Directive Model (i.e. DIRECTIVE_TYPE_MONTH = 'MONTH') -  // this column is unique // no default value 
                                                // this Column Must only be SEEDED, NOT stored.    - but -   // if we need to add additional CUSTOM directives, this column is always required if we are doing STORE // must NOT be null
                                                // this Column could hold numeric values as string, i.e. 40 = every 40 days. but before i use 40 for CALCULATION, i will cast it to INTEGER to avoid error

            $table->boolean('is_active')->default(1); // if any ACTIVE(ONGOING) AssetUnit / AssetPool / (i.e. enterprise Service) is using this directive , it should NOT be DEACTIVATED
            $table->string('name')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directives');
    }
};
