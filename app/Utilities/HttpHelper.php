<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('http_get')) {
    function http_get(string $url, array $headers = [], array $params = [])
    {
        return Http::withHeaders($headers)->get($url, $params)->json();
    }
}

if (!function_exists('http_post')) {
    function http_post(string $url, array $headers = [], array $data = [])
    {
        return Http::withHeaders($headers)->post($url, $data)->json();
    }
}

if (!function_exists('http_put')) {
    function http_put(string $url, array $headers = [], array $data = [])
    {
        return Http::withHeaders($headers)->put($url, $data)->json();
    }
}

if (!function_exists('http_delete')) {
    function http_delete(string $url, array $headers = [], array $params = [])
    {
        return Http::withHeaders($headers)->delete($url, $params)->json();
    }
}
