<?php

use App\Models\AssetUnit;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asset_units', function (Blueprint $table) {
            $table->id()->from(10000);

            $table->foreignId('enterprise_id')->constrained('enterprises');
            $table->foreignId('asset_main_id')->constrained('asset_mains');
            $table->foreignId('payer_id')->constrained('payers');
            $table->foreignId('directive_id')->constrained('directives');
            $table->foreignId('penalty_id')->constrained('penalties');


            $table->boolean('is_payment_by_term_end')->default(1); 
                                            //
                                            // 1. this column is checked for the following directive types ONLY =
                                            //                        // = Directive::DIRECTIVE_TYPE_WEEK, Directive::DIRECTIVE_TYPE_MONTH, Directive::DIRECTIVE_TYPE_YEAR,
                                            //
                                            // IF (Directive::DIRECTIVE_TYPE_WEEK || Directive::DIRECTIVE_TYPE_MONTH || Directive::DIRECTIVE_TYPE_YEAR) {

                                                        
                                                                    // POSSIBLE VALUES
                                                                    //
                                                                    //
                                                                    // if (is_payment_by_term_end === 1) {
                                                                                
                                                                            // ------------------- NUMBER of DAYS until the next payment will NOT be NEEDED here (i.e. instead we jump though TERMs, and use their start_date & end_date ) ----------------------//
                                                                                // call function for the following operation
                                                                                        // > payment will be calculated at the END DATE of each term, Payments are made at the END of each fixed period/term, regardless of the start date
                                                                                                //          - Monthly: Payment is due at the end of each calendar month.
                                                                                                //          - Weekly: Payment is due at the end of each calendar week .
                                                                                                //          - Yearly: Payment is due at the end of the calendar year.      
                                                                    // }
                                                                    //
                                                                    //
                                                                    // else if (is_payment_by_term_end === 0) {
                                                                        
                                                                            // ---------------- NUMBER of DAYS until the next payment will NOT be found on the database (i.e. instead we use similar date on the next TERM to generate the next bill, see the examples belos) ------------------//
                                                                                // call function for the following operation
                                                                                        // > payments are based on the date the person joins, and repeat on that same day for each period/term
                                                                                                //          - Monthly: If a person starts on the 17th, the payment is due on the 17th of the following month, then every 17th thereafter.
                                                                                                //          - Weekly: Payment is due every 7 days from the start date.
                                                                                                //          - Yearly: Payment is due after anniversary of the start date each year. 
                                                                                                //                              If a person starts on the 17th OCTOBER, the payment is due on the Following YEAR at 17th of the OCTOBER MONTH, then every Next Year of 17th of that same month (i.e. OCTOBER) thereafter.
                                                                    // }
                                            // } 


                                            // 2. this column will NOT be checked for the following directive types
                                            //                        // = 1. Directive::DIRECTIVE_TYPE_14,       2. Directive::DIRECTIVE_TYPE_15,        3. or any Custom directives that will be added after   
                                            //                                                                                             // for such directive types, payment will be calculated after the fixed date defined with the directive type
                                            // else { 

                                                                    // WE WILL NOT CHECK (is_payment_by_term_end)
                                                                            // FOR this directive types the column is_payment_by_term_end, SHOULD NEVER be checked

                                                    
                                                                            // ---------------- NUMBER of DAYS until the next payment will be found on the database
                                                        
                                                    
                                            //}

                                            


            $table->integer('penalty_starts_after_days');    // this should ONLY be number of days // any integer/number stored in this column is considered as number of days
            $table->decimal('service_termination_penalty', 10, 2); // this penalty will be calculated When/IF the payer want to terminate his service of his asset usage

            $table->decimal('price_principal', 10, 2);

            $table->string('payment_status')->default(AssetUnit::ASSET_UNIT_PAYMENT_NOT_STARTED);


            $table->date('start_date');
            $table->date('end_date'); // if the this arrangement is terminated before the pre defined end_date - this should be assigned the date the order is terminated 
                                      // (when the payer terminates his arrangement with the asset before the predefined end_date , the end_date column will be replaced by the value of the termination date)

            $table->date('original_end_date'); // this is the end_date upon that the payer will use the asset as , its filled when the payer registers in to the asset and stats to use this asset initially, 
                                                                    // IF this arrestment is TERMINATED before the predefined end_date, 
                                                                                                // 
                                                                                                // the end_date will assume the date the arrangment is terminated     and      this original_end_date will assume the end_date inserted initially

            

            $table->boolean('is_terminated')->default(0);
            $table->boolean('payer_can_terminate')->default(0);


            $table->boolean('is_engaged')->default(0); // this is for, is the asset CURRENTLY Under-Way (asset Unit) - or - Under-Use (Asset Pool)?
                                                                                                                    //
                                                                                                                    // AssetUnit 
                                                                                                                            // 0 = The Asset (i.e. house) is CURRENTLY NOT Occupied by SOMEONE else (i.e. vacant) - is used to make individual asset FREE, so that it can be used by Anyone else
                                                                                                                                                                // - CURRENTLY Under Contract with someone else
                                                                                                                            // 1 = The Asset (i.e. house) is CURRENTLY OCCUPIED by SOMEONE else (i.e. NOT vacant)               - is used to make individual asset NOT Free, so that it can NOT be used by Anyone else
                                                                                                                                                                // - CURRENTLY NOT under Contract with anybody else    
                                                                                                                    

            $table->string('asset_unit_name')->nullable();
            $table->longText('asset_unit_description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_units');
    }
};
