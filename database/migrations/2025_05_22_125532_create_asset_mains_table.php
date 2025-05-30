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

            $table->boolean('is_active')->default(1); // this is for, is the asset currently available (Asset Unit  - or -  Asset Pool) ?
                                                                                                                    //
                                                                                                                    // AssetUnit 
                                                                                                                            // 0 = The Asset (i.e. house) can NOT be used   - is used to make an individual Asset Un-Usable
                                                                                                                            // 1 = The Asset (i.e. house) can be USED       - is used to make an individual Asset Usable
                                                                                                                    // AssetPool
                                                                                                                            // 0 = The Asset (i.e. ekub or edir) is currently on HOLD / NOT Available for participation     - is used to make a group Asset Un-Available - make it un Attendeble
                                                                                                                            // 1 = The Asset (i.e. ekub or edir) is currently Open / AVAILABLE for participation            - is used to make a group Asset Available    - make it Attendeble


            
            

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
