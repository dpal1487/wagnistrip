<?php

namespace App\Services;

use GuzzleHttp\Client;

class AmadeusApiClient
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct($apiKey, $apiSecret, $baseUrl = 'https://test.api.amadeus.com/v2/')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;
    }

    public function get($endpoint, $params = [])
    {
        $client = new Client([
            'auth' => [$this->apiKey, $this->apiSecret],
            'base_uri' => $this->baseUrl,
        ]);

        $response = $client->get($endpoint, [
            'query' => $params,
        ]);

        return json_decode($response->getBody(), true);
    }
}
