<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('request_response')) {
    function request_response($code, $status, $data)
    {
        return response()->json([
            'code' => $code, 'msg' => $status, 'data' => $data
        ], 200);
    }
}

if (!function_exists('get_authorized_user')) {
    function get_authorized_user()
    {
        return auth()->user();
    }
}

if (!function_exists('get_source_ip')) {
    function get_source_ip()
    {
        $realip = '0.0.0.0';
        $possible_headers = [
            'HTTP_TRUE_CLIENT_IP',
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($possible_headers as $header) {
            if (isset($_SERVER[$header])) {
                $realip = $_SERVER[$header];
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $realip = explode(',', $realip)[0];
                }
                break;
            } elseif ($env_ip = getenv($header)) {
                $realip = $env_ip;
                break;
            }
        }

        return filter_var($realip, FILTER_VALIDATE_IP) ? $realip : '0.0.0.0';
    }
}

if (!function_exists('get_authorized_user_role')) {
    function get_authorized_user_role()
    {
        return implode(" ", Auth::user()->roles->pluck('name')->toArray());
    }
}