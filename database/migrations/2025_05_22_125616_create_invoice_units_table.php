<?php

use App\Models\InvoiceUnit;
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
        Schema::create('invoice_units', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_code'); // Essential if the a multiple different invoices are paid once, which need to be considered at once // i.e. we use it during payment Callback & other places // so for them we use the same invoice code // it is not unique

            $table->foreignId('asset_unit_id')->constrained('asset_units');

            $table->uuid('transaction_id_system'); // this is our transaction id, which is created all the time // this should NOT be unique because when paying using invoice_code, all the invoices under that invoice code should have the same uuid (i.e. transaction_id_system)
            $table->string('transaction_id_banks')->nullable(); // this is the transaction id that comes from the banks during callback
            
            $table->date('start_date');
            $table->date('end_date');

            // these TWO will be added during payment
            $table->decimal('price', 10, 2); // is (the date differences multiplied by the asset_unit principal_price)
            $table->decimal('penalty', 10, 2)->default(0); // is the calculated penalty price // during payment it will be added with the price column 

            $table->boolean('immune_to_penalty')->default(0);

            $table->string('status')->default(InvoiceUnit::INVOICE_STATUS_NOT_PAID); // this column is like enum
            $table->date('paid_date')->nullable(); // initially it is NULL when the bill is generated // set when payer pays this invoice or invoice group (with similar invoice_code)

            $table->string('payment_method')->nullable(); // should be NULL initially

            //
            $table->json('request_payload')->nullable(); // if there is any request payload i need to store in the database // i will put it in this column
            






            // the columns that will be added below in the future here, are intended for the return data from the banks
            $table->json('response_payload')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_units');
    }
};
