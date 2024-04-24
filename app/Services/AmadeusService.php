<?php

namespace App\Services;

use Amadeus\Amadeus;
use InvalidArgumentException;

class AmadeusService
{
    public function getClient(): Amadeus
    {
        $clientId = env('AMADEUS_API_KEY');
        $clientSecret = env('AMADEUS_API_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            throw new InvalidArgumentException('Missing Amadeus API credentials.');
        }
        return Amadeus::builder($clientId, $clientSecret)->build();
    }
}
