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
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();

            $table->string('penalty_type')->default(\App\Models\Penalty::PENALTY_TYPE_FLAT);
                                                            // IF = FLAT = PENALTY_TYPE_FLAT
                                                                        //
                                                                        // this means the percent value of the  = principal_price, from : - asset_units or asset_pools table, FOR the specific asset the payer is paying for
                                                                        //
                                                                        // so, IF based on this penalty type    - IF the penalty start date passes without the customer paying his/her principal_price payment
                                                                        //                     //
                                                                        //                     // an additional penalty will be calculated once for each UNPAID TERM
                                                                        //                     // the penalty of the payer will NOT Increase other than this as the days goes by
                                                                        //

                                                            // IF = DAILY = PENALTY_TYPE_DAILY
                                                                        //
                                                                        // this means the DAILY percent value of the  = principal_price, from : - asset_units or asset_pools table, FOR the specific asset the payer is paying for
                                                                        //                     //
                                                                        //                     // an additional penalty will be calculated for "each day that comes after the penalty start date", of that UNPAID term NON STOP, 
                                                                        //                     // Until the payer clears his payment for that Un-Paid TERM, for every day that passes an additional penalty amount will be added
                                                                        //                     // the penalty of the payer will INCREASE as the days goes by
                                                                        //


            $table->decimal('percent_of_principal_price', 4, 2);

            // Composite unique key (composite key)
            $table->unique(['penalty_type', 'percent_of_principal_price'] /*, 'unique_penalty_price' */ );


            $table->boolean('is_active')->default(1); // if any ACTIVE(ONGOING) AssetUnit / AssetPool / (i.e. enterprise Service) is using this penalty , it should NOT be DEACTIVATED


            // ABRHAM check
            // Penalty should be Global and any penalty type should be used by any enterprise in the system, so there is NO need to put a column here to show who created this penalty 
            // BUT should this have a column to identify who created this penalty ?

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
