<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class DetectDevice
{
    /**
     * Handle an incoming request.
     * 
     * abrham remember
     * DETECTING devices is OPTIONAL // this middleware could be disabled at will in bootstrap/app.php
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $userAgent = $request->header('User-Agent');
        $ip = \App\Services\AppService::getIp(); // Added to get the IP address
        $deviceType = 'other'; // Default value


        // Skip logging and caching for local/internal IPs (SKIP caching and logging for IPs = [127.0.0.1  - or -  localhost])
        //                                                                                                              // if the IP or url/full_url contains = [127.0.0.1  - or -  localhost] then Do NOT log
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return $next($request);
        }


        // Build a unique cache key per IP + User-Agent (Added caching logic)
        $cacheKey = 'device_logged_' . md5($ip . '|' . $userAgent);
        // Check if already logged recently (i.e., in the past 1 hour)
        $alreadyLogged = Cache::has($cacheKey); // This fetches a value only if the cache expiry time has NOT passed
        

        // 1. Manually check the token without setting auth()->user()
        $userId = null;
        $description = null;

        $accessToken = $request->bearerToken();

        if ($accessToken) {
            // Find token using Sanctum's personal access token model
            $token = PersonalAccessToken::findToken($accessToken);

            // Check if the token exists and has abilities
            if ($token && $token->abilities) {
                // Directly get user info from token without setting auth()->user()
                $user = $token->tokenable; // This is the associated user model

                // Safety checks before accessing user properties
                if ($user && is_object($user) && class_exists(get_class($user))) {
                    // Get the user ID and the user class
                    $userId = $user?->id ?? null;
                    $description = get_class($user); // This will give you 'App\Models\User' or any other user model

                    // Now you can use $userId and $description for logging or other operations
                }
            }
        }



        // Handle missing User-Agent
        if (!$userAgent) {
            $deviceType = 'unknown';
        }

        // Priority-based detection

        // 1. Postman
        elseif (stripos($userAgent, 'PostmanRuntime') !== false) {
            $deviceType = 'postman';
        }

        // 2. curl
        elseif (stripos($userAgent, 'curl') !== false) {
            $deviceType = 'curl';
        }

        
        
        // 3. Apple (iPhone, iPad, iOS)
        //
        //
        // 3.1. Apple (iPhone, iPad, iOS)   -   the keyword "ios" exists in Axios = SO Axios check (avoid false IOS (Apple) detection) ,     // also optionally skip logging
        //
        elseif (stripos($userAgent, 'axios') !== false) {
            $deviceType = 'axios';
        }
        //
        // 3.2. Apple (iPhone, iPad, iOS)   -   the REAL (iPhone, iPad, iOS) CHECK
        //
        elseif (
            (
                stripos($userAgent, 'iPhone') !== false ||
                stripos($userAgent, 'iPad') !== false ||
                preg_match('/(^|[^a-zA-Z])iOS([^a-zA-Z]|$)/i', $userAgent)
                /* stripos($userAgent, 'iOS') !== false */ 
            ) 
            && 
            stripos($userAgent, 'axios') === false
        ) {
            $deviceType = 'apple';
        }



        // 4. Android
        elseif (stripos($userAgent, 'Android') !== false) {
            $deviceType = 'android';
        }

        // 5. Web browsers (Chrome, Firefox, Safari, Edge, Opera, Brave, etc.)
        elseif (
            stripos($userAgent, 'Chrome') !== false ||
            stripos($userAgent, 'Firefox') !== false ||
            stripos($userAgent, 'Safari') !== false ||
            stripos($userAgent, 'Edg') !== false ||             // Edge includes "Edg"
            stripos($userAgent, 'OPR') !== false ||             // Opera includes "OPR"
            stripos($userAgent, 'Brave') !== false ||
            stripos($userAgent, 'Mozilla') !== false            // Catch-all for unknown browser variants
        ) {
            $deviceType = 'web';
        }

        // Attach the device type to the request so it can be used later or in (i.e. [in controller/service])
        $request->merge(['device_type' => $deviceType]);



        // Log all details for tracking/debugging (only if not already logged)
        if (!$alreadyLogged) {  // This runs only if the expiry time of the cache has passed OR the key was never cached

            \App\Models\DeviceTraffic::create([
                'user_id' => $userId, // Log the user_id
                'user_id_description' => $description, // Log the dynamic user model name

                'device_type' => $deviceType,
                'user_agent' => $userAgent,
                'ip' => $request->ip(),
                'ip_got_using_custom_function' => \App\Services\AppService::getIp(),
                'ip_behind_proxy_or_broadcast' => \App\Services\AppService::getIp_of_Requests_that_came_from__ProxyServers_or_BroadcastedLocally(),
                'ip_advanced_deep_tracing' => \App\Services\AppService::getIpAdvanced_deep_tracing(),
                'url' => $request->fullUrl(),
            ]);

            // Log all details for tracking/debugging
            \Illuminate\Support\Facades\Log::info('Device detected', [
                'user_id' => $userId, // Log the user_id
                'user_id_description' => $description, // Log the dynamic user model name
                
                'device_type' => $deviceType,
                'user_agent' => $userAgent,
                'ip' => $request->ip(),
                'ip_got_using_custom_function' => \App\Services\AppService::getIp(),
                'ip_behind_proxy_or_broadcast' => \App\Services\AppService::getIp_of_Requests_that_came_from__ProxyServers_or_BroadcastedLocally(),
                'ip_advanced_deep_tracing' => \App\Services\AppService::getIpAdvanced_deep_tracing(),
                'url' => $request->fullUrl(),
            ]);

            // **NEW**: Store a value in the cache that expires in 1 hour
            Cache::put($cacheKey, true, now()->addHour());

        }

        return $next($request);
    }
}
