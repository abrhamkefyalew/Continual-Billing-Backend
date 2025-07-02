<?php

namespace App\Services;

class AppService
{
    public static function getIp()
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }





    /**
     * get the REAL IP
     *     //         // get real IP, get ip real
     *     //
     *     IF
     *           - request is PROXIED, reverse Proxied
     *     or if
     *           - request is sent from Locally during broadcasting (i.e. Echo and Pusher or WebSockets)
     *                  // Laravel Echo or Pusher client is running locally, and it's making the request directly from your device back to your own server.
     * 
     */
    public static function getIp_of_Requests_that_came_from__ProxyServers_or_BroadcastedLocally(): string
    {
        $request = request();

        $forwarded = $request->header('X-Forwarded-For');
        if ($forwarded) {
            // May contain comma-separated list of IPs
            $ipList = explode(',', $forwarded);
            return trim($ipList[0]);
        }

        return $request->header('X-Real-IP')
            ?? $request->ip();
    }



}
