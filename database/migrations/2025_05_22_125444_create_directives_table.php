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
                                                // this Column Must only be SEEDED, NOT stored.    - but -   // this column is always required if storing

            $table->boolean('is_active')->default(1);
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
