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
        Schema::create('asset_mains', function (Blueprint $table) {
            $table->id()->from(100000);

            $table->foreignId('enterprise_id')->constrained('enterprises');
            $table->string('asset_name');
            $table->string('asset_description')->nullable();

            $table->boolean('is_active')->default(1);

            $table->boolean('is_occupied')->default(0);
            
            $table->string('type');  // the values are  = AssetMain::ASSET_MAIN_OF_ASSET_UNIT_TYPE  or   AssetMain::ASSET_MAIN_OF_ASSET_POOL_TYPE

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_mains');
    }
};
