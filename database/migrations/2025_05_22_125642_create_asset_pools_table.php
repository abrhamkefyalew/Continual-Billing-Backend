<?php

use App\Models\AssetPool;
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
        Schema::create('asset_pools', function (Blueprint $table) {
            $table->id()->from(10000);

            $table->foreignId('enterprise_id')->constrained('enterprises');
            $table->foreignId('asset_main_id')->constrained('asset_mains');
            $table->foreignId('payer_id')->nullable()->constrained('payers'); // for now this one will NOT be needed // i have removed it from the fillable list in AssetPool Model (abrham check)
            $table->foreignId('directive_id')->constrained('directives');
            $table->foreignId('penalty_id')->constrained('penalties');

            
            $table->integer('penalty_starts_after_days');    // this should ONLY be number of days // any integer/number stored in this column is considered as number of days
            $table->decimal('service_termination_penalty', 10, 2); // this penalty will be calculated When/IF the payer want to terminate his service of his asset usage

            $table->decimal('price_principal', 10, 2);

            $table->string('payment_status')->default(AssetPool::ASSET_POOL_PAYMENT_NOT_STARTED);

            
            $table->date('start_date');
            $table->date('end_date'); // if the CONTENT on this ROW (i.e. event, campaign . . .) is terminated before the pre defined end_date - this should be assigned the date the order is terminated 
                                      // (when the enterprise terminates the CONTENT on this ROW (i.e. event, campaign . . .) assetPool, before the predefined end_date , the end_date column will be replaced by the value of the termination date)

            $table->date('original_end_date'); // this is the end_date upon that the payer will use the asset as , its filled when the payer registers in to the asset and stats to use this asset initially, 
                                                                    // IF this arrestment is TERMINATED before the predefined end_date, 
                                                                                                // 
                                                                                                // the end_date will assume the date the arrangment is terminated     and      this original_end_date will assume the end_date inserted initially

            

            $table->boolean('is_terminated')->default(0);
            $table->boolean('payer_can_terminate')->default(0);


            $table->boolean('is_engaged')->default(0); // this is for: - is the asset CURRENTLY Under-Way (asset Unit) - or - Under-Use (Asset Pool)?
                                                                                                                    //
                                                                                                                    // AssetPool
                                                                                                                            // 0 = The Asset (i.e. ekub or edir) is CURRENTLY NOT underway.  - the group asset/service/event is NOT started   - is used to END/finish the event (i.e. group asset) 
                                                                                                                                                                // - Participation is NO longer allowed Until the event is Re-Initiated
                                                                                                                            // 1 = The Asset (i.e. ekub or edir) is CURRENTLY UNDERWAY          - the group asset/service/event is STARTED       - is used to initiate/Start the event (i.e. group asset) 
                                                                                                                                                                // - Participation is Allowed since the event is Currently Initiated


            $table->string('asset_pool_name')->nullable();
            $table->longText('asset_pool_description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_pools');
    }
};
