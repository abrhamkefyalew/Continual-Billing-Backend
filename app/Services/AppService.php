<?php

namespace App\Services;

class AppService
{
    /**
     * Get the client IP address, handling proxies, and forwarded headers.
     *
     * This function checks common HTTP headers to determine the real client IP,
     * especially when the request is passing through reverse proxies or load balancers.
     * It filters out private and reserved IP ranges to avoid logging non-client IPs.
     * If no valid IP is found, it falls back to the default `request()->ip()`.
     *
     * @return string|null The most reliable public IP, or null if no valid IP is found.
     */
    public static function getIp()
    {
        // Loop through each of the common headers to try and find the real client IP
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            // Check if the header exists in the $_SERVER superglobal (or Laravel's request helper)
            if (array_key_exists($key, $_SERVER) === true) {
                // If the header contains multiple comma-separated IPs, process each IP
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe - Clean up the IP address to remove any surrounding spaces

                    // Validate the IP, excluding private or reserved IP ranges
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip; // Return the first valid public IP found
                    }
                }
            }
        }

        // If no valid IP was found from headers, fall back to Laravel's built-in IP retrieval method
        return request()->ip(); // it will return the server IP if the client IP is not found using this method. // i.e. Will return the server's IP or the client's IP if no proxy is involved
    }





    /**
     * get the REAL IP
     *     //         // get real IP, get ip real
     *     //
     *     IF
     *           - request is PROXIED, reverse Proxied
     *     or if
     *           - request is sent from Locally during broadcasting (i.e. Echo and Pusher or WebSockets)
     *                  // Laravel Echo or Pusher client is running locally, and it's making the request directly from our device back to our own server.
     * 
     * 
     * Get the real client IP from requests that might come from proxies or broadcasting tools (like Laravel Echo or WebSockets).
     *
     * This method will look for headers like X-Forwarded-For or X-Real-IP, which are often used in scenarios
     * involving reverse proxies, load balancers, or when the request originates from a local machine
     * (e.g., when using Laravel Echo, WebSockets, or Pusher).
     *
     * @return string The real IP address of the client or the last known IP in the chain.
     * 
     */
    public static function getIp_of_Requests_that_came_from__ProxyServers_or_BroadcastedLocally(): string
    {
        $request = request();

        // Look for the 'X-Forwarded-For' header, which is commonly set by proxies or load balancers
        $forwarded = $request->header('X-Forwarded-For');


        if ($forwarded) {
            // 'X-Forwarded-For' may contain a comma-separated list of IPs (first one is the client IP)
            $ipList = explode(',', $forwarded);
            return trim($ipList[0]); // Return the first IP in the list, which is typically the client's IP
        }

        // If no 'X-Forwarded-For' header exists, fallback to other common headers like 'X-Real-IP'
        return $request->header('X-Real-IP') // Return real IP
            ?? $request->ip(); // or fall back to Laravel's default IP retrieval
    }






    /**
     * MORE ADVANCED
     *       With the COMBINED Functionally of both the above TWO Functions
     *                                                       //
     *                                                       1. getIp()
     *                                                       2. getIp_of_Requests_that_came_from__ProxyServers_or_BroadcastedLocally()
     * 
     * 
     * 
     * Get the most accurate client IP address from the current request.
     *
     * This method intelligently checks a series of HTTP headers to determine
     * the real client IP, even when the request is passing through proxies,
     * load balancers, reverse proxies (e.g., NGINX, AWS ELB), or broadcasting tools
     * like Laravel Echo or WebSockets.
     *
     * It also filters out internal/reserved IPs (like 127.0.0.1 or 10.x.x.x)
     * to avoid logging non-client IPs.
     *
     * @return string|null The most reliable public IP, or null if none found.
     */
    public static function getIpAdvanced_deep_tracing(): ?string
    {
        // Get the current request instance from Laravel helper
        $request = request();

        /**
         * Step 1: Headers to check for the real client IP.
         * 
         * These headers are typically set by reverse proxies and load balancers.
         * 'X-Forwarded-For' may contain a comma-separated list of IPs.
         * We want the *first valid* public IP in the chain.
         */
        $headerKeys = [
            'X-Forwarded-For',           // Most common proxy header
            'X-Real-IP',                 // Alternative to X-Forwarded-For
            'HTTP_CLIENT_IP',           // Used by some proxies/load balancers
            'HTTP_X_FORWARDED_FOR',     // Same as X-Forwarded-For but with HTTP_ prefix
            'HTTP_X_FORWARDED',         // Variant
            'HTTP_X_CLUSTER_CLIENT_IP', // Load balancer cluster IP header
            'HTTP_FORWARDED_FOR',       // Less common
            'HTTP_FORWARDED',           // Less common
        ];

        // Loop through each header in order of trustworthiness
        foreach ($headerKeys as $key) {
            // Try to get the header from Laravel's request object or fallback to $_SERVER
            $ipList = $request->header($key) ?? ($_SERVER[$key] ?? null);

            if ($ipList) {
                /**
                 * Some headers (e.g., X-Forwarded-For) contain multiple IPs separated by commas.
                 * We take the first IP in the list, which is usually the original client.
                 */
                foreach (explode(',', $ipList) as $ip) {
                    $ip = trim($ip); // Remove any whitespace

                    /**
                     * Validate the IP format and ensure it's not a private or reserved IP address.
                     *
                     * FILTER_FLAG_NO_PRIV_RANGE => Exclude private IP ranges like 192.168.x.x
                     * FILTER_FLAG_NO_RES_RANGE  => Exclude reserved ranges like 0.0.0.0 or 240.0.0.0+
                     */
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip; // Return the first valid public IP found
                    }
                }
            }
        }

        /**
         * Step 2: If no valid IP is found in headers, fallback to Laravel's native method.
         *
         * This typically returns $_SERVER['REMOTE_ADDR'], which might be the IP of a proxy or container.
         */
        $fallbackIp = $request->ip();

        // As a safety net, validate the fallback IP
        if (filter_var($fallbackIp, FILTER_VALIDATE_IP)) {
            return $fallbackIp;
        }

        /**
         * Step 3: If everything fails, return null.
         *
         * This is very rare but ensures the function doesn't break our app.
         */
        return null;
    }




}
