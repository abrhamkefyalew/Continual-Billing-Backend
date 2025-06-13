<?php

namespace App\Services\Api\V1\ModelServices\InvoiceUnit;

use Carbon\Carbon;
use App\Models\Penalty;
use App\Models\AssetUnit;
use App\Models\InvoiceUnit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;

class InvoiceUnitService
{


    


    public function generateAll(Request $request) {

        $startDate = Carbon::parse('2025-12-01');
        $endDate = Carbon::now();   // generate bill until now
        // $endDate = Carbon::parse('2026-03-01');  // generate bill until this day


        // OUTPUT
        // From 2025-12-01 to 2025-12-31
        // From 2026-01-01 to 2026-01-31
        // From 2026-02-01 to 2026-02-28

        $assetUnit = AssetUnit::where('payer_id', auth()->guard()->user()->id)->where('id', $request['enterprise_service_id']);

        $this->generateBill($startDate, $endDate, $assetUnit);
        $this->updatePenaltyForMultipleAssetUnitsForAllPayers($assetUnit); // penalty calculation end date must always be until TODAY,     - so ALWAYS this is set automatically as NOW(),    - NO other value can NOT be set from other customer input or db input  // i have set now end_date inside the updatePenalty() function itself
        $this->getInvoices($assetUnit); // end_date should NOT be set - we fetch all invoices including the future invoices that are generated for pre payment
    }




    //
    /**
     * Get all full months between two dates, giving start and end date of each month.
     *
     * @param Carbon $startDate  The starting date
     * @param Carbon $endDate    The ending date    // the date in which the customer comes to the system to pay his due
     * @return Collection        A collection of arrays with start_date and end_date
     */
    public function generateBill(Carbon $startDate /* this is the SERVICE START_DATE or END_DATE of the last invoice payment of that SERVICE */ /* 2025-02-11 */ , Carbon $endDate /* 2025-02-17 */, AssetUnit $assetUnit) /* : Collection | String */
    {


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // TODO
        //
        // Always check if start_date and end_date isset() [if they are either from REQUEST or DB TABLEs] before doing the later Operations on them,  
        // check if any column isset() from the DB TABLEs before using them or you will get ERROR
        // if the are from request check them only if the are NOT required
        //
        // 1. First check: -  if the database fetch variable is valid  (it could be Object or Collection)
        //                                                                                        if you want to continue if object is valid              if ($object) {}                                      // if object is valid do logic in if clause
        //                                                                                        if you want to continue if collection is valid          if (!$collection->isEmpty()) {}                      // if not empty do logic in if clause
        //                                                                                        //
        //                                                                                        if you want to abort if object is NOT valid             if (!$object) {ERROR}                                // if object is NOT valid ABORT      
        //                                                                                        if you want to abort if collection is NOT valid         if ($collection->isEmpty()) {ERROR}                  // if collection is empty ABORT
        //
        //
        // 2. Then DO: -  if you want to use one column value from the $object or $collection variable. check that column existence using to isset()
        //                                                                                        if (isset($object->end_date)) {}               or     if (isset($collection[x]->status)) {}                  // if object valid do logic in if clause
        //                                                                                        if (!isset($object->end_date)) {ERROR}         or     if (!isset($collection[x]->status)) {ERROR}            // if object is NOT valid ABORT
        //
        //
        // 3. if it is request variable , and it is NOT validated as "required" in Form request.  and if we may need it , also check if isset()
        //                                                                                        if (isset($request['end_date'])) {}                                                                          // if object valid do logic in if clause
        //                                                                                        if (!isset($request['end_date'])) {}                                                                         // if object is NOT valid ABORT
        //
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        $endDate = $endDate ?? Carbon::today();
        // $endDate = Carbon::parse(NOW()); // NOT USED since Resource consuming b/c of redundancy
        

        // customer request values
        $request[] = "";

        // from policys table
        $penaltyStartsAfter = 10; // days
        //
        // ONLY one of the following columns will be created for the penalty table
        $penaltyPerDay = 20;
        $penaltyAmount = 200;

        // from customer_services table
        //
        $pricePerMonth = 1000;
        $status = $assetUnit->status;
        $allowedToEndAtAnyDate = "true / false";
        $beginDate = "";


        // from invoice table



        // user id from TOKEN
        $customerId = "";


        // Create an empty collection to store the results, so that i can use it in while loop below
        $months = collect();

        // Start from the first day of the start month
        $current = $startDate->copy()->startOfMonth(); // 2025-02-01



        
        if ($status == "PAYMENT_NOT_STARTED") {


            // i.e. and some assetUnits does NOT have END date, they are infinite // Ex. - EDIR,    - Rent without contract
            // i.e. some assetUnits HAVE END DATE , so they are finite            // Ex. - EKUB, ,  - Rent with contract that have end date 
            //
            //
            //
            // if the end date of assetUnit is set  (Ex. - EKUB ,   - Rent with contract that have end date)        
            if (isset($enterprizeService->end_date)) {

                $assetUnitEndDate = Carbon::parse($assetUnit->end_date);


                // the end date the user inserts OR now() is greater than the date where the Actual AssetUnit Service ends,  then we should return error
                if ($endDate->gt($assetUnitEndDate)) {
                    return "the bill calculation date in which calculation will be done upto must always be less than the date the Actual service ends";
                }
            }



            if ($endDate->lt($startDate)) {
                return "ERROR : - the end date in which your invoice will be generated upto -- should be greater than the start date from your invoice will be calculated after. (i.e. the bill generation date[end date] should always be greater than service start date)";
            }


            // get the start date the customer_service first started
            // Get the first day that the customer starts to use the customer_service
            $startDateOfThisLot = $startDate; // 2025-02-11

            // Get the last day of this month (28, 29, 30, 31 are handled automatically)
            $monthEnd = $current->copy()->endOfMonth(); // 2025-02-28

            // if ($endDate < $monthEnd) {
            if ($endDate->lt($monthEnd)) {  // i will use this IF the above if does NOT compare as I expected // i.e. the above may want me to change the dates to string format so that I can compare them

                // the man is terminating the service he is using and pay the last price and stop using the service
                // but since this is the first payment he should send explicitly that he wants stop service now (and pay his last payment)
                if ($request['terminate_service_now'] != true) {
                    return "no pending payment";
                    // do  =>  break;  - or -  ($current->addMonth()) then continue; - in the while loop, if you are in while loop
                } 

                if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate == true) {
                    $monthEnd = $endDate;
                    $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                } 

                if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate != true) {

                    // IF (the enterprise admin is the ONLY one who is allowed to terminate service from the payer) {
                    //        //  . . . . then we should return ERROR
                    // }


                    $monthEnd = $current->copy()->endOfMonth(); // 2025-02-28
                    $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next "if condition" will be respected (i.e. the next "if condition" will be resolved to be TRUE)
                } 
                
            } else if ($endDate->eq($monthEnd)) {
                if (!isset($request['terminate_service_now'])) {
                    $a = "hi";
                    // do  the same $a='hi'; - if you are in the while loop,
                }
                if ($request['terminate_service_now'] != true) {
                    $a = "hi";
                    // do  the same $a='hi'; - if you are in the while loop,
                } 
                if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate == true) {
                    $monthEnd = $endDate;
                    $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                } 

                if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate != true) {

                    // IF (the enterprise admin is the ONLY one who is allowed to terminate service from the payer) {
                    //        //  . . . . then we should return ERROR
                    // }

                    $monthEnd = $current->copy()->endOfMonth(); // 2025-02-28
                    $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next "if condition" will be respected (i.e. the next "if condition" will be resolved to be TRUE)
                } 
            }


            // $dateDifference = $monthEnd - $startDateOfThisLot;
            $dateDifference = $monthEnd->diffInDays($startDateOfThisLot);
            $dateDifferenceFinal = $dateDifference + 1;

        
            // PRICE
            $pricePerDay = $pricePerMonth / 30 /* 30 is days of month // check abrham , john*/;
            $priceForThisLot = $pricePerDay * $dateDifferenceFinal;



            /*

                // SINCE PENALTY IS CALCULATED SEPARATELY IN ANOTHER METHOD ( this code is NOT needed here, i.e. this code is overkill )

                // PENALTY
                $penaltyPriceForThisLot = 0;
                
                $monthEndOfThisMonth_UsedToCheck_AgainstPenalty = $current->copy()->endOfMonth();
                //
                // $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty + $penaltyStartsAfter;
                $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty->copy()->addDays($penaltyStartsAfter);  // this calculation wasted resource // but there is nothing you can do it is essential for the following if
                


                // METHOD 1
                //
                // if ($endDate > $penaltyStartDate) {
                if ($endDate->gt($penaltyStartDate)) {
                    
                    if ("PENALTY_TYPE_DAILY") {

                        // $numberOfPenaltyDays = $endDate - $penaltyStartDate;
                        $numberOfPenaltyDays = $endDate->diffInDays($penaltyStartDate);
                    
                        $penaltyPriceForThisLot = $numberOfPenaltyDays * $penaltyPerDay;        // $penaltyPerDay = [ principal price / number of days in this Term (i.e. month) ] * $penalty->percent_of_principal_price

                    }

                    if ("PENALTY_TYPE_FLAT") {
                        $penaltyPriceForThisLot = $penaltyAmount;
                    }



                    
                }

                */



            
            $invoice = InvoiceUnit::create([
                'invoice_code' => Str::uuid(), // used when a payer selects multiple invoices and pays those multiple selected invoices
                'customer_id' => $customerId,
                'enterprize_id' => '$enterprize_id',
                'start_date' => $startDateOfThisLot,
                'end_date' => $monthEnd,
                'price' => $priceForThisLot,
                // 'penalty' => $penaltyPriceForThisLot,
                // 'number_of_penalty_days' => $numberOfPenaltyDays,
                'immune_to_penalty' => 'T / F',  // this is for all invoice tables, if this is set to T -> will be skipped during penalty calculation of NOT_PAID invoices
                'status' => "NOT_PAID", // paid / NOT_Paid   // REAL VAULE = NOT Paid, since we are only generating bill/invoice , NOT paid,    - this will be paid ONLY when the CALLBACK hits
                'paid_date' => NOW(),
            ]);



            // customer_service table (Update)
            // $enterprizeService = $enterprizeService->update([
            //     'status' => 'PAYMENT_STARTED',
            // ]);
            //
            $assetUnit->save();


            // Otherwise, this is a full month! Save it.
            // this is just to let us see (Log)
            $months->push([
                'start_date' => $startDateOfThisLot->format('Y-m-d'), // Save as date string
                'end_date' => $monthEnd->format('Y-m-d'),     // Save as date string
            ]);

            // Move to the next month
            // $current->addMonth(); // 2025-03-01;
            
        }






        if ($status == "PAYMENT_STARTED") {


            // i.e. and some assetUnits does NOT have END date, they are infinite // Ex. - EDIR,    - Rent without contract
            // i.e. some assetUnits HAVE END DATE , so they are finite            // Ex. - EKUB, ,  - Rent with contract that have end date 
            //
            //
            //
            // if the end date of assetUnit is set  (Ex. - EKUB ,   - Rent with contract that have end date)        
            if (isset($enterprizeService->end_date)) {

                $assetUnitEndDate = Carbon::parse($assetUnit->end_date);


                // the end date the user inserts OR now() is greater than the date where the Actual enterprise ends,  then we should return error
                if ($endDate->gt($assetUnitEndDate)) {
                    return "the bill calculation date in which calculation will be done upto must always be less than the date the Actual service ends";
                }
            }


            // after the customer logs in he will get all the services (enterprize services) he is subscribed to
            //      // when he chooses one of the enterprize services, i will catch it in object named $enterprizeService
            //      //      // then i will use that $enterprizeService, in the code below,  i.e. in INVOICEs and other purposes

            $lastInvoice = $assetUnit->invoices()->latest()->first();

            if (!$lastInvoice) {
                return "ERROR: - no valid Last invoice";
            }

            if (!$lastInvoice->end_date) {
                return "ERROR: - the last invoice has no valid End Date";
            }

            $lastInvoiceEndDate = Carbon::parse($lastInvoice->end_date); // 2025-02-28

            

            if ($endDate->lt($lastInvoiceEndDate)) {
                return "ERROR : - the end date in which your invoice will be generated upto should be greater than the start date from your invoice will be calculated after. (i.e. the bill generation date[end date] should always be greater than the last bill generation date)";
            }

            $lastInvoiceEndDateEndOfMonth = $lastInvoiceEndDate->copy()->endOfMonth(); // 2025-02-28

            if ($lastInvoiceEndDate->ne($lastInvoiceEndDateEndOfMonth)) {
                return "error, the last invoice end date must be equal to the end of the month.  i.e. the last invoice should have been paid until the end of that month, unless the enterprize service for that payer is terminated correctly, So in your case we are assuming the service you selected now is terminated";
                // or we can handle it even if the last invoice payment end date is not at the end of that month, by checking the following if 
                        // if ($lastInvoiceEndDate->ne($lastInvoiceEndDateEndOfMonth)) { 
                                // and if true = calculate the payment of the rest of the days of that month by using (the daily price that we will calculate)
                            // }

            }






            // 
            $current = $lastInvoiceEndDate->copy()->startOfMonth(); // 2025-02-01
            
            // now lets MOVE to the NEXT MONTH of the last invoice Date we get
            // $current = $current->addMonth(); // NOT USED // 2025-03-01
            $current->addMonth(); // 2025-03-01

            // Loop as long as current month start is before the end date
            while ($current->lt($endDate)) {

                // Get the first day of this month
                $monthStart = $current->copy()->startOfMonth();

                // Get the last day of this month (28, 29, 30, 31 are handled automatically)
                $monthEnd = $current->copy()->endOfMonth();




                $priceForThisLot = $pricePerMonth; /* check abrham , john */


                // if ($endDate < $monthEnd) {
                if ($endDate->lt($monthEnd)) {  // i will use this, IF the above if does NOT compare as I expected // i.e. the above may want me to change the dates to string format so that I can compare them
    
                    // the man is terminating the service he is using and pay the last price and stop using the service
                    // but since this is the first payment he should send explicitly that he wants stop service now (and pay his last payment)
                    if ($request['terminate_service_now'] != true) {
                        return "no pending payment";
                        // do  =>  break;  - or -  ($current->addMonth()) then continue; - in the while loop, if you are in while loop
                    } 
    
                    if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate == true) {
                        $monthEnd = $endDate;

                         // $dateDifference = $monthEnd - $monthStart;
                        $dateDifference = $monthEnd->diffInDays($monthStart);
                        $dateDifferenceFinal = $dateDifference + 1;

                    
                        // PRICE
                        $pricePerDay = $pricePerMonth / 30 /* 30 is days of month // check abrham , john*/;
                        $priceForThisLot = $pricePerDay * $dateDifferenceFinal;


                        $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                    } 
    
                    if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate != true) {

                        // IF (the enterprise admin is the ONLY one who is allowed to terminate service from the payer) {
                        //        //  . . . . then we should return ERROR
                        // }


                        $monthEnd = $current->copy()->endOfMonth(); // 2025-02-28
                        $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                    } 
                    
                } else if ($endDate->eq($monthEnd)) {
                    if (!isset($request['terminate_service_now'])) {
                        $a = "hi";
                        // do  the same $a='hi'; - if you are in the while loop,
                    }
                    if ($request['terminate_service_now'] != true) {
                        $a = "hi";
                        // do  the same $a='hi'; - if you are in the while loop,
                    } 
                    if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate == true) {
                        $monthEnd = $endDate;
                        $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                    } 
    
                    if ($request['terminate_service_now'] == true && $allowedToEndAtAnyDate != true) {

                        // IF (the enterprise admin is the ONLY one who is allowed to terminate service from the payer) {
                        //        //  . . . . then we should return ERROR
                        // }


                        $monthEnd = $current->copy()->endOfMonth(); // 2025-02-28
                        $assetUnit->status = 'PAYMENT_STARTED'; // this will ensure that the next if will be respected (i.e. the next if will be resolved to be TRUE)
                    } 
                }
    



                //
            //
            // ******************************************************************************************************************************************************************************************************************************************

            // i.e. and some assetUnits does NOT have END date, they are infinite // Ex. - EDIR,    - Rent without contract
            // i.e. some assetUnits HAVE END DATE , so they are finite            // Ex. - EKUB, ,  - Rent with contract that have end date 
            //
            //
            //
            // if the end date of assetUnit is set  (Ex. - EKUB ,   - Rent with contract that have end date)        
            if (isset($assetUnit->end_date)) {

                $assetUnitEndDate = Carbon::parse($assetUnit->end_date);









                // this is WRONG IF condition,  [ - NOT used - ] - [ DO NOT use ]
                // Because the payer could pay his due , even after the service end_date passes, 
                //             payer also could ask bill generation even after the service end_date passes
                //             he could also be given penalty even after the service end_date passes, and the penalty will be generated long after the actual service end_date , IF he does NOT pay his due
                //
                //
                //  WHAT WE SHOULD DO IS    (  i.e. for = = = if ($endDate->gt($assetUnitEndDate))  )
                //
                //          1. BIll generation      =      upto $assetUnit->end_date
                //          2. Penalty Generation   = ONLY upto NOW() / upto today() - - - i.e. ( upto Carbon::now() )
                //
                // the end date the user inserts OR now() is greater than the date where the Actual enterprise ends,  then we should return error
                if ($endDate->gt($assetUnitEndDate)) {
                    return "the bill calculation date in which calculation will be done upto must always be less than the date the Actual service ends";
                }



            }
            // ******************************************************************************************************************************************************************************************************************************************


            
    

                


                /*

                // SINCE PENALTY IS CALCULATED SEPARATELY IN ANOTHER METHOD ( this code is NOT needed here, i.e. this code is overkill )

                // PENALTY
                $penaltyPriceForThisLot = 0;
                
                $monthEndOfThisMonth_UsedToCheck_AgainstPenalty = $current->copy()->endOfMonth();
                //
                // $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty + $penaltyStartsAfter;
                $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty->copy()->addDays($penaltyStartsAfter);  // this calculation wasted resource // but there is nothing you can do it is essential for the following if
                


                // METHOD 1
                //
                // if ($endDate > $penaltyStartDate) {
                if ($endDate->gt($penaltyStartDate)) {
                    
                    if ("PENALTY_TYPE_DAILY") {

                        // $numberOfPenaltyDays = $endDate - $penaltyStartDate;
                        $numberOfPenaltyDays = $endDate->diffInDays($penaltyStartDate);
                    
                        $penaltyPriceForThisLot = $numberOfPenaltyDays * $penaltyPerDay;        // $penaltyPerDay = [ principal price / number of days in this Term (i.e. month) ] * $penalty->percent_of_principal_price

                    }

                    if ("PENALTY_TYPE_FLAT") {
                        $penaltyPriceForThisLot = $penaltyAmount;
                    }



                    
                }

                */


                



                $invoice = InvoiceUnit::create([
                    'invoice_code' => NULL, // initially null,   SET during payment - used when a payer selects multiple invoices and pays those multiple selected invoices // it is used for callback only, // E.x. the banks will send only one ID (i.e. invoice_code as callback)
                    'enterprise_service_id' => $assetUnit->id,
                    'customer_id' => $customerId,
                    'enterprize_id' => '$enterprize_id',
                    'start_date' => $monthStart,
                    'end_date' => $monthEnd,
                    'price' => $priceForThisLot,
                    // 'penalty' => $penaltyPriceForThisLot,
                    // 'number_of_penalty_days' => $numberOfPenaltyDays,
                    'immune_to_penalty' => 'T / F',  // this is for all invoice tables, if this is set to T -> will be skipped during penalty calculation of NOT_PAID invoices
                    'status' => "NOT_PAID", // paid / NOT_Paid   // REAL VAULE = NOT Paid, since we are only generating bill/invoice , NOT paid,    - this will be paid ONLY when the CALLBACK hits
                    'paid_date' => NOW(),
                ]);
    
    
    
                // customer_service table (Update)
                // $enterprizeService = $enterprizeService->update([
                //     'status' => 'PAYMENT_STARTED',
                // ]);
                //
                $assetUnit->save();
    
    







                // If the last day of this month is beyond our desired end date, we stop
                // if ($monthEnd->gt($endDate)) {
                //     break; // Exit the loop
                // }

                // Otherwise, this is a full month! Save it.
                // this is just to let us see (Log)
                $months->push([
                    'start_date' => $monthStart->format('Y-m-d'), // Save as date string
                    'end_date' => $monthEnd->format('Y-m-d'),     // Save as date string
                ]);

                // Move to the next month
                $current->addMonth();
            }

        }





        






        

        // Finally, return the collection of months
        return $months;
    }






    /**
     * Penalty will only be generated/Updated for the already generated invoices/bills ONLY (those already generaged  Bills/invoices should also be UNPAID invoices)
     * 
     * so NO new invoice/bill will be generated here
     * 
     * Penalty will be Updated for
     *      1. AssetUnits  -> with payment_status = (PAYMENT_STARTED & PAYMENT_LAST)
     *      1. Invoices    -> with status         = (NOT_PAID)
     * 
     */
    public function updatePenaltyForMultipleAssetUnitsForAllPayers(InvoiceUnit $invoiceUint)
    {



    }




    /**
     * Penalty will only be generated/Updated for the already generated invoices/bills ONLY (those already generaged  Bills/invoices should also be UNPAID invoices)
     * 
     * so NO new invoice/bill will be generated here, ONLY PENALTY is updated in this function
     * 
     * Penalty will be Updated for
     *      1. AssetUnits  -> with payment_status = (PAYMENT_STARTED & PAYMENT_LAST)
     *      1. Invoices    -> with status         = (NOT_PAID)
     * 
     */
    public function updatePenaltyForMultipleAssetUnitsForSinglePayer()
    {
        
        try {

            DB::transaction(function () {

                // i.e. the below code does UPDATE PENALTY Operation for INVOICEs under          
                //                      ||                                            1. a MULTIPLE AssetUnits   -> that have status (PAYMENT_STARTED & PAYMENT_LAST)    
                //                      ||                                                     BUT for    
                //                      ||                                            2. a single payer
                //                      ||
                //                      \/

                $endDate = Carbon::today();  // penalty calculation end date must always be until TODAY,     - so ALWAYS this is set automatically as NOW(),    - NO other value can NOT be set from other customer input or db input

                
                $allowedPaymentStatusTypes = [
                    AssetUnit::ASSET_UNIT_PAYMENT_STARTED,
                    AssetUnit::ASSET_UNIT_PAYMENT_LAST,
                ];

                $assetUnits = AssetUnit::where('payer_id', auth()?->guard()?->user())
                        ->whereIn('payment_status', $allowedPaymentStatusTypes)
                        ->get();

                $assetUnitIds = $assetUnits->pluck('id');

                $unpaidInvoiceUnits = InvoiceUnit::whereIn('asset_unit_id', $assetUnitIds)
                        ->where('status', InvoiceUnit::INVOICE_STATUS_NOT_PAID)
                        ->where('immune_to_penalty', 0)
                        ->whereDate('end_date', '<=', today()->toDateString()) // is added, NOT to include bills that are generated for FUTURE pay In the Penalty Calculation = (i.e. NOT to include the invoices generated for i.e. for the next 4 months in the future), done NOT to include pre/advance/ahead/invoice generations in penalty calculation
                        // ->whereDate('end_date', '<=', now()->toDateString())
                        // ->whereDate('end_date', '<=', Carbon::today()->toDateString())
                        // ->whereDate('end_date', '<=', Carbon::now()->toDateString())
                        ->get();


                //

                foreach ($unpaidInvoiceUnits as $invoiceUnit) {


                    $penaltyStartsAfterDays = $invoiceUnit->assetUnit->penalty_starts_after_days;



                    $invoiceUnitEndDate = Carbon::parse($invoiceUnit->end_date);

                    


                    // is_terminated


                    // DO NOT use - 
                    // BECAUSE = there could be bill generated for FUTURE/Advance/Ahead payments - 
                    // so those payments could have "END_DATE" that is greater than "TODAY's Date (i.e. $endDate = Carbon::today();)"
                    // SO this exception will break our logic, NEVER use this 
                    // if ($endDate /* Carbon::now() */->lt($invoiceUnitEndDate)) {
                    //     throw new \Exception('ERROR : - the end date in which your penalty will be calculated upto should be greater than the start date from your invoice will be calculated after. (i.e. the penalty generation date[end date] should always be greater than the last bill generation date)');
                    // }


                    

                    // $invoiceUnitEndDateEndOfMonth = $invoiceUnitEndDate->copy()->endOfMonth(); // 2025-02-28


                    // FOR NOW lets NOT check this IF condition
                    //
                    // this condition could HAPPEN IF
                    //          if the service is TERMINATED or
                    //          if the service is ended NORMALLY at the end date
                    //
                    //
                    // so if the service is NOT terminated  - or -  NOT ended normally
                    //      we must return ERROR if the following if condition becomes true
                    //
                    // if ($invoiceUnitEndDate->ne($invoiceUnitEndDateEndOfMonth)) {
                    //     throw new \Exception('error, the last invoice end date must be equal to the end of the month.  i.e. the last invoice should have been paid until the end of that month, unless the enterprize service for that payer is terminated correctly, So in your case we are assuming the service you selected now is terminated');
                    //     // or we can handle it even if the last invoice payment end date is not at the end of that month, by checking the following if 
                    //             // if ($lastInvoiceEndDate->ne($invoiceUnitEndDateEndOfMonth)) { 
                    //                     // and if true = calculate the payment of the rest of the days of that month by using (the daily price that we will calculate)
                    //                 // }

                    // }



                    // PENALTY price
                    $penaltyPriceForThisLot = 0;

                    // PRINCIPAL price
                    $principalPriceForThisLot = $invoiceUnit?->assetUnit?->price_principal;
                    
                    //
                    // $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty + $penaltyStartsAfterDays;
                    $penaltyStartDate = $invoiceUnitEndDate->copy()->addDays($penaltyStartsAfterDays);   

                    //
                    // if ($endDate > $penaltyStartDate) {
                    if ($endDate->gt($penaltyStartDate)) {
                        
                        // METHOD 1
                        if ($invoiceUnit?->assetUnit?->penalty?->penalty_type === Penalty::PENALTY_TYPE_DAILY) {

                            $percentOfPrincipalPrice = $invoiceUnit?->assetUnit?->penalty?->percent_of_principal_price;
                            $daysInCurrentMonth = $invoiceUnitEndDate->daysInMonth;
                            $penaltyPerDay = ($principalPriceForThisLot / $daysInCurrentMonth) * $percentOfPrincipalPrice;


                            // $numberOfPenaltyDays = $endDate - $penaltyStartDate;
                            $numberOfPenaltyDays = $endDate->diffInDays($penaltyStartDate);
                            $penaltyPriceForThisLot = $numberOfPenaltyDays * $penaltyPerDay;        // $penaltyPerDay = [ principal price / number of days in this Term (i.e. month) ] * $penalty->percent_of_principal_price
                        }

                        // METHOD 1
                        if ($invoiceUnit->assetUnit->penalty->penalty_type === Penalty::PENALTY_TYPE_FLAT) {

                            $percentOfPrincipalPrice = $invoiceUnit?->assetUnit?->penalty?->percent_of_principal_price;

                            $penaltyPriceForThisLot = $principalPriceForThisLot * $percentOfPrincipalPrice;
                        }



                        
                    }



                    $updated = $invoiceUnit->update([
                        'penalty' => $penaltyPriceForThisLot,
                    ]);

                    if (!$updated) {
                        throw new \Exception('Failed to update penalty for invoice ID ' . $invoiceUnit->id);
                    }


                    
                }
                
            });


            return [
                'success' => true,
                'message' => 'All penalties updated successfully.',
            ];

        } catch (\Exception $e) {

            Log::error('Penalty update failed', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Penalty update failed.',
                'error' => $e->getMessage(),
            ];
        }

        




    }



    /**
     * Penalty will only be generated/Updated for the already generated invoices/bills ONLY (those already generaged  Bills/invoices should also be UNPAID invoices)
     * 
     * so NO new invoice/bill will be generated here
     * 
     * Penalty will be Updated for
     *      1. AssetUnits  -> with payment_status = (PAYMENT_STARTED & PAYMENT_LAST)
     *      1. Invoices    -> with status         = (NOT_PAID)
     * 
     */
    public function updatePenaltyForSingleAssetUnitForSinglePayer($assetUnit)
    {



    }




    /**
     * Penalty will only be generated/Updated for the already generated invoices/bills ONLY (those already generaged  Bills/invoices should also be UNPAID invoices)
     * 
     * so NO new invoice/bill will be generated here
     * 
     */
    public function updatePenaltyPrototype()
    {

        // i.e. the below code does UPDATE PENALTY Operation for INVOICEs under          
        //                      ||                                            1. a SINGLE AssetUnit           
        //                      ||                                            2. a SINGLE payer
        //                      ||
        //                      \/

        $assetUnit = AssetUnit::find(10089);

        $request[] = "";


        // ONLY one of the following columns will be created for the penalty table
        $penaltyPerDay = $assetUnit->penalty->penaltyPerDay;    // i.e. 20
        $penaltyAmountFlat = $assetUnit->penalty->penaltyAmountFlat;  // i.e. 200


        // from penalties table
        $penaltyStartsAfter = $assetUnit->penalty->penalty_starts_after; // only in days (i.e. 10 days)
        
        $status = $assetUnit->status;


        $endDate = Carbon::now();  // penalty calculation end date must always be until TODAY,     - so ALWAYS this is set automatically as NOW(),    - NO other value can NOT be set from other customer input or db input


        



        // if ($status == "PAYMENT_STARTED") {  // COMMENTED BECAUSE // THIS CONDITION is USELESS here



            $unpaidInvoices = $assetUnit->invoiceUnits()
                ->where('status', 'NOT_PAID')
                ->get();



            /*

            //
            // for the cronjob -> USE this instead
            //                          // if you are using cronjob at midnight to run the jobs, us the following commented code instead
            //
            // $unpaidInvoices = Invoice::where('status', 'NOT_PAID')->get();
            //
            // 
            //
            // foreach ($unpaidInvoices as $invoice) {
            //     $assetUnit = $invoice->assetUnit()->first();
            // }
            //

            */


            foreach ($unpaidInvoices as $invoice) {


                $invoiceEndDate = Carbon::parse($invoice->end_date);

                // PENALTY
                $penaltyPriceForThisLot = 0;
                
                //
                // $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty + $penaltyStartsAfter;
                $penaltyStartDate = $invoiceEndDate->copy()->addDays($penaltyStartsAfter);   // this calculation wasted resource // but there is nothing you can do it is essential for the following if

                // METHOD 1
                //
                // if ($endDate > $penaltyStartDate) {
                if ($endDate->gt($penaltyStartDate)) {
                    
                    if ("PENALTY_TYPE_DAILY") {

                        // $numberOfPenaltyDays = $endDate - $penaltyStartDate;
                        $numberOfPenaltyDays = $endDate->diffInDays($penaltyStartDate);
                    
                        $penaltyPriceForThisLot = $numberOfPenaltyDays * $penaltyPerDay;        // $penaltyPerDay = [ principal price / number of days in this Term (i.e. month) ] * $penalty->percent_of_principal_price

                    }

                    if ("PENALTY_TYPE_FLAT") {
                        $penaltyPriceForThisLot = $penaltyAmountFlat;
                    }



                    
                }



                
            }




            


            //
            //
            // ******************************************************************************************************************************************************************************************************************************************

            // i.e. and some assetUnits does NOT have END date, they are infinite // Ex. - EDIR,    - Rent without contract
            // i.e. some assetUnits HAVE END DATE , so they are finite            // Ex. - EKUB, ,  - Rent with contract that have end date 
            //
            //
            //
            // if the end date of assetUnit is set  (Ex. - EKUB ,   - Rent with contract that have end date)        
            if (isset($assetUnit->end_date)) {

                $assetUnitEndDate = Carbon::parse($assetUnit->end_date);


                // this is WRONG IF condition,  [ - NOT used - ] - [ DO NOT use ]
                // Because the payer could pay his due , even after the service end_date passes, 
                //             payer also could ask bill generation even after the service end_date passes
                //             he could also be given penalty even after the service end_date passes, and the penalty will be generated long after the actual service end_date , IF he does NOT pay his due
                //
                //
                //  WHAT WE SHOULD DO IS
                //          1. BIll generation      = upto $assetUnit->end_date
                //          2. Penalty Generation   = upto NOW() / upto today() - - - i.e. ( upto Carbon::now() )
                //
                // the end date the user inserts OR now() is greater than the date where the Actual enterprise ends,  then we should return error
                if ($endDate->gt($assetUnitEndDate)) {
                    return "the bill calculation date in which calculation will be done upto must always be less than the date the Actual service ends";
                }
            }
            // ******************************************************************************************************************************************************************************************************************************************









            // from customer_services table

            // after the customer logs in he will get all the services (enterprize services) he is subscribed to
            //      // when he chooses one of the enterprize services, i will catch it in object named $enterprizeService
            //      //      // then i will use that $enterprizeService, in the code below,  i.e. in INVOICEs and other purposes

            $lastInvoice = $assetUnit->invoices()->latest()->first();
            $lastInvoiceEndDate = Carbon::parse($lastInvoice->end_date); // 2025-02-28

            if ($endDate /* Carbon::now() */->lt($lastInvoiceEndDate)) {
                return "ERROR : - the end date in which your penalty will be calculated upto should be greater than the start date from your invoice will be calculated after. (i.e. the penalty generation date[end date] should always be greater than the last bill generation date)";
            }

            $lastInvoiceEndDateEndOfMonth = $lastInvoiceEndDate->copy()->endOfMonth(); // 2025-02-28

            if ($lastInvoiceEndDate->ne($lastInvoiceEndDateEndOfMonth)) {
                return "error, the last invoice end date must be equal to the end of the month.  i.e. the last invoice should have been paid until the end of that month, unless the enterprize service for that payer is terminated correctly, So in your case we are assuming the service you selected now is terminated";
                // or we can handle it even if the last invoice payment end date is not at the end of that month, by checking the following if 
                        // if ($lastInvoiceEndDate->ne($lastInvoiceEndDateEndOfMonth)) { 
                                // and if true = calculate the payment of the rest of the days of that month by using (the daily price that we will calculate)
                            // }

            }

            // 
            $current = $lastInvoiceEndDate->copy()->startOfMonth(); // 2025-02-01
            
            // now lets MOVE to the NEXT MONTH of the last invoice Date we get
            // $current = $current->addMonth(); // NOT USED // 2025-03-01
            $current->addMonth(); // 2025-03-01




            // PENALTY
            $penaltyPriceForThisLot = 0;
                    
            $monthEndOfThisMonth_UsedToCheck_AgainstPenalty = $current->copy()->endOfMonth();
            //
            $penaltyStartDate = $monthEndOfThisMonth_UsedToCheck_AgainstPenalty + $penaltyStartsAfter; // the COMMENT is WRONG- this calculation wasted resource // but there is nothing you can do it is essential for the following if


            //
            // METHOD 1
            //
            // if ($endDate > $penaltyStartDate) {
            // if ($endDate->gt($penaltyStartDate)) {  // i will use this IF the above if does NOT compare as I expected // i.e. the above may want me to change the dates to string format so that I can compare them
            //     // $numberOfPenaltyDays = $endDate - $penaltyStartDate;
            //     $numberOfPenaltyDays = $endDate->diffInDays($penaltyStartDate);
            //
            //     $penaltyPriceForThisLot = $numberOfPenaltyDays * $penaltyPerDay;
            // }
                
        
        
        
        
        // }

        
    }


    public function getInvoices($enterprizeService) 
    {
        // end_date should NOT be set
        // bill fetch date is all, NO end date should be set, a payer could PRE generate Bill for the future if he wants to PRE PAY or just pre generate BILL   - so those bill should be shown here too ,     
        // - so we do NOT set $endDate as now() or any other value.     - getInvoices is fetch all from invoice table for that enterprise service that the customer is subscribed in



    }


    // public function toCall_TheMothCalculatorFunction()
    // {
    //     //
    //     // ------------------------------------
    //     // Example usage:

    //     // Define my start and end dates
    //     $startDate = Carbon::parse('2025-12-01');
    //     $endDate = Carbon::parse('2026-03-01');

    //     // Call the function
    //     $fullMonths = $this->fullMonthStartEndIntervals($startDate, $endDate);

    //     // Output the results
    //     foreach ($fullMonths as $month) {
    //         echo "From {$month['start_date']} to {$month['end_date']}\n";
    //     }

    //     // OUTPUT
    //     // From 2025-12-01 to 2025-12-31
    //     // From 2026-01-01 to 2026-01-31
    //     // From 2026-02-01 to 2026-02-28

    // }



    // public function toCall_TheMothCalculatorFunction_Two()
    // {
    //     //
    //     // ------------------------------------
    //     // Example usage:

    //     // Define my start and end dates
    //     $startDate = Carbon::parse('2025-02-11');
    //     $endDate = Carbon::parse('2025-05-13');

    //     // Call the function
    //     $fullMonths = $this->fullMonthStartEndIntervals($startDate, $endDate);

    //     // Output the results
    //     foreach ($fullMonths as $month) {
    //         echo "From {$month['start_date']} to {$month['end_date']}\n";
    //     }

    //     // OUTPUT
    //     // From 2025-02-01 to 2025-02-28
    //     // From 2025-03-01 to 2025-03-31
    //     // From 2025-04-01 to 2025-04-30
    // }

}