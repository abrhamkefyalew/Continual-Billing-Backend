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

            $table->integer('penalty_starts_after_days');    // this should ONLY be number of days // any integer/number stored in this column is considered as number of days

            $table->decimal('percent_of_principal', 4, 2);   // this column holds the percent value of the  = principal_price, from : - asset_units or asset_pools table, FOR the specific asset the payer is paying for
                                                             //
                                                             // so, IF based on this column    - IF the penalty start date passes without the customer paying his/her principal_price payment
                                                             //                     //
                                                             //                     // an additional penalty will be calculated once for each UNPAID term
                                                             //                     // the penalty of the payer will NOT Increase other than this as the days goes by
                                                             //


            $table->decimal('percent_of_principal_daily', 4, 2);    // this column holds the percent value of the  = principal_price, from : - asset_units or asset_pools table, FOR the specific asset the payer is paying for
                                                                    //                     //
                                                                    //                     // an additional penalty will be calculated for each UNPAID term NON STOP, for every day that passes, Until the payer clears his payment for that term
                                                                    //                     // the penalty of the payer will INCREASE as the days goes by
                                                                    //

            $table->decimal('service_termination_penalty', 10, 2); // this penalty will be calculated When/IF the payer want to terminate his service of his asset usage

            $table->boolean('is_active')->default(1);

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
