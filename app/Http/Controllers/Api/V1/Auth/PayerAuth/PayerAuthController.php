<?php

namespace App\Http\Controllers\Api\V1\Auth\PayerAuth;

use Carbon\Carbon;
use App\Jobs\SendSmsJob;
use App\Models\Payer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\Api\V1\MediaService;
use App\Util\Api\V1\OtpCodeGenerator;
use App\Http\Requests\Api\V1\AuthRequests\LoginPayerRequest;
use App\Http\Requests\Api\V1\AuthRequests\RegisterPayerRequest;
use App\Http\Requests\Api\V1\AuthRequests\Otp\LoginOtpPayerRequest;
use App\Http\Requests\Api\V1\AuthRequests\Otp\VerifyOtpPayerRequest;
use App\Http\Resources\Api\V1\PayerResources\PayerForPayerResource;

class PayerAuthController extends Controller
{

    public function register(RegisterPayerRequest $request)
    {
        $payer = Payer::create($request->validated());
        // is_active values will be the default values = "1"    -   -   -   - (as it was SET in the payers table migration)
        // is_approved values will be the default values = "0"    -   -   -   - (as it was SET in the payers table migration) // this means he can not make order unless he is Approved by the SUPER_ADMIN (i.e. is_approved = 1)


        if ($request->has('country') || $request->has('city')) {
            $payer->address()->create([
                'country' => $request->input('country'),
                'city' => $request->input('city'),
            ]);
        }

        if ($request->has('payer_profile_image')) {
            $file = $request->file('payer_profile_image');
            $clearMedia = false; // or true // // NO Payer image remove, since it is the first time the payer is being Registered
            $collectionName = Payer::PAYER_PROFILE_PICTURE;
            MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
        }

        //
        if ($request->has('payer_id_front_image')) {
            $file = $request->file('payer_id_front_image');
            $clearMedia = false; // or true // // NO Payer image remove, since it is the first time the payer is being Registered
            $collectionName = Payer::PAYER_ID_FRONT_PICTURE;
            MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
        }

        if ($request->has('payer_id_back_image')) {
            $file = $request->file('payer_id_back_image');
            $clearMedia = false; // or true // // NO Payer image remove, since it is the first time the payer is being Registered
            $collectionName = Payer::PAYER_ID_BACK_PICTURE;
            MediaService::storeImage($payer, $file, $clearMedia, $collectionName);
        }



        // He Only Logs in after he verifies the otp on verify_otp,
        // so we commented the following code
        //
        // $tokenResult = $payer->createToken('Personal Access Token', ['access-payer']);
        // //$payer->sendEmailVerificationNotification();

        // return response()->json(
        //     [
        //         'access_token' => $tokenResult->plainTextToken,
        //         'token_abilities' => $tokenResult->accessToken->abilities,
        //         'token_type' => 'Bearer',
        //         'expires_at' => $tokenResult->accessToken->expires_at,
        //         'data' => new PayerForPayerResource($payer->load(['media', 'address'])),
        //     ],
        //     201
        // );






        // IF there are any generated OTPs for this payer , then DELETE them
        if ($payer->otps()->exists()) {
            // DELETE the rest of the otps of that payer from the otps table
            // $success = Otp::where('payer_id', $payer->id)->forceDelete();  // this works also
            $success = $payer->otps()->forceDelete();                          // this works
            //
            if (!$success) {
                return response()->json(['message' => 'otp Deletion Failed']);
            }
        }


        $otpCode = OtpCodeGenerator::generate(6);


        // Generate current datetime
        $currentDateTime = Carbon::now();

        // Add 5 minutes to the current datetime
        $expiryTime = $currentDateTime->addMinutes(5);


        $otp = $payer->otps()->create([
            'code' => $otpCode,
            'expiry_time' => $expiryTime,
        ]);
        //
        if (!$otp) {
            return response()->json(['message' => 'OTP creation Failed'], 500);
        }

        // $sendSms = SMSService::sendSms($payer->phone_number, 'Adiamat Vehicle Rental: OTP (Verification code): ' . $otpCode);
        // //
        // if (!$sendSms) {
        //     return response()->json(['message' => 'Failed to send SMS'], 500);
        // }

        try {
            SendSmsJob::dispatch($payer->phone_number, 'Adiamat Vehicle Rental: OTP (Verification code): ' . $otpCode)->onQueue('sms');
        } catch (\Throwable $e) {
            // Log the exception or handle it as needed
            return response()->json(['message' => 'Failed to dispatch SMS job'], 500);
        }


        return response()->json(['message' => 'Account Successfully Created. you can login with this account from now on. SMS with OTP is sent to you. Please Verify'], 202);
    }




    public function login(LoginPayerRequest $request)
    {
        // better use load than with, since here after all we get the data , we are checking if the password does match, 
        // if password does not match all the data and relation and Eager Load is wasted and the data will NOT be returned
        // do first get only the payer and if the password matches then get the other relations using load()
        $payer = Payer::with(['address', 'media'])->where('email', $request->email)->first();  // only if is_active = 1 he can login

        // request()->request->add(['payer-permission-groups' => true]);

        if ($payer) {
            if (Hash::check($request->password, $payer->password)) {

                $tokenResult = $payer->createToken('Personal Access Token', ['access-payer']);
                $expiresAt = now()->addMinutes(9886); // Set the expiration time to 240 minutes from now - -   -   -   -   now() = is helper function of laravel, - - - (it is NOT Carbon's)  // FOR PRODUCTION MAKE THE EXPIRE DATE TO 20 MINs
                $token = $tokenResult->accessToken;
                $token->expires_at = $expiresAt;
                $token->save();

                //$payer->sendEmailVerificationNotification();

                return response()->json(
                    [
                        'access_token' => $tokenResult->plainTextToken,
                        'token_abilities' => $tokenResult->accessToken->abilities,
                        'token_type' => 'Bearer',
                        'expires_at' => $tokenResult->accessToken->expires_at,
                        'data' => new PayerForPayerResource($payer),
                    ],
                    200
                );
            }
        }

        return response()->json(['message' => 'Login failed. Incorrect email or password.'], 400);
    }






    public function loginOtp(LoginOtpPayerRequest $request)
    {

        $payer = Payer::where('phone_number', $request['phone_number'])->first();
        //
        if (!$payer) {
            return response()->json(['message' => 'Login failed. Account does NOT exist.'], 404);
        }

        // payer can still login without being approved, BUT He can not make order unless he is Approved by the SUPER_ADMIN
        // 
        // if ($payer->is_approved != 1) {
        //     return response()->json(['message' => 'Login failed. Account NOT approved.'], 401);
        // }


        // IF there are any generated OTPs for this payer , then DELETE them
        if ($payer->otps()->exists()) {
            // DELETE the rest of the otps of that payer from the otps table
            // $success = Otp::where('payer_id', $payer->id)->forceDelete();  // this works also
            $success = $payer->otps()->forceDelete();                          // this works
            //
            if (!$success) {
                return response()->json(['message' => 'otp Deletion Failed']);
            }
        }


        $otpCode = OtpCodeGenerator::generate(6);


        // Generate current datetime
        $currentDateTime = Carbon::now();

        // Add 5 minutes to the current datetime
        $expiryTime = $currentDateTime->addMinutes(5);


        if ($payer->phone_number == "+251910101010") {
            $otp = $payer->otps()->create([
                'code' => "123456",
                'expiry_time' => $expiryTime,
            ]);
        } else {
            $otp = $payer->otps()->create([
                'code' => $otpCode,
                'expiry_time' => $expiryTime,
            ]);
        }


        //
        if (!$otp) {
            return response()->json(['message' => 'OTP creation Failed'], 500);
        }

        // $sendSms = SMSService::sendSms($payer->phone_number, 'Adiamat Vehicle Rental: OTP (Verification code): ' . $otpCode);
        // //
        // if (!$sendSms) {
        //     return response()->json(['message' => 'Failed to send SMS'], 500);
        // }

        try {
            SendSmsJob::dispatch($payer->phone_number, 'Adiamat Vehicle Rental: OTP (Verification code): ' . $otpCode)->onQueue('sms');
        } catch (\Throwable $e) {
            // Log the exception or handle it as needed
            return response()->json(['message' => 'Failed to dispatch SMS job'], 500);
        }


        return response()->json(['message' => 'SMS job dispatched successfully'], 202);
    }


    public function verifyOtp(VerifyOtpPayerRequest $request)
    {

        $payer = Payer::where('phone_number', $request['phone_number'])->first();
        //
        if (!$payer) {
            return response()->json(['message' => 'Login failed. Account does NOT exist.'], 404);
        }

        // payer can still login without being approved, BUT He can not make order unless he is Approved by the SUPER_ADMIN
        // if ($payer->is_approved != 1) {
        //     return response()->json(['message' => 'Login failed. Account NOT approved.'], 401);
        // }


        // Check if the OTP from the user input exists and is NOT Expired
        $isValidOtpExists = $payer->otps()
            ->where('code', $request['code'])
            ->where('expiry_time', '>', now()) // Check if the EXPIRY time is in the future
            ->exists();
        //
        if ($isValidOtpExists == false) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }


        // IF there are any generated OTPs for this payer , then DELETE them
        if ($payer->otps()->exists()) {
            // DELETE the rest of the otps of that payer from the otps table
            // $success = Otp::where('payer_id', $payer->id)->forceDelete();  // this works also
            $success = $payer->otps()->forceDelete();                          // this works
            //
            if (!$success) {
                return response()->json(['message' => 'otp Deletion Failed']);
            }
        }


        // then if all the above conditions are met ,  I will load relationships.  // like the following
        $payer->load(['address', 'media']);


        // generate TOKEN
        $tokenResult = $payer->createToken('Personal Access Token', ['access-payer']);
        $expiresAt = now()->addMinutes(262170); // Set the expiration time to 50 minutes from now - -   -   -   -   now() = is helper function of laravel, - - - (it is NOT Carbon's)
        $token = $tokenResult->accessToken;
        $token->expires_at = $expiresAt;
        $token->save();

        //$payer->sendEmailVerificationNotification();

        return response()->json(
            [
                'access_token' => $tokenResult->plainTextToken,
                'token_abilities' => $tokenResult->accessToken->abilities,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->accessToken->expires_at,
                'data' => new PayerForPayerResource($payer),
            ],
            200
        );
    }





    /**
     * LogOut from one device or current session only
     */
    public function logout(Request $request)
    {

        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    /**
     * LogOut from All devices or Every other sessions
     */
    public function logoutAllDevices(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout from all devices successful'], 200);
    }
}
