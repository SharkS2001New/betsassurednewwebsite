<?php

namespace App\Facades;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Http
{
    private static $client;

    public static function init()
    {
        if (!self::$client) {
            self::$client = new Client();
        }
    }

    public static function get($url, $queryParams = [], $headers)
    {
        self::init();
        
        try {
            // Make the HTTP GET request with headers and query parameters
            $response = self::$client->request('GET', $url, [
                'headers' => $headers,
                'query' => $queryParams
            ]);

            // Check if the request was successful (status code 200)
            if ($response->getStatusCode() === 200) {
                // Decode the JSON response body
                return json_decode($response->getBody()->getContents(), true);
            }
        } catch (RequestException $e) {
            // Handle exceptions (network issues, 4xx, or 5xx errors)
            echo 'Error: ' . $e->getMessage();
        }

        return null;
    }
}
