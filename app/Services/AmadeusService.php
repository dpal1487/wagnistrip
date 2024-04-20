<?php

namespace App\Services;

use Amadeus\Amadeus;

class AmadeusService
{
    public function getClient(): Amadeus
    {
        $clientId = env('AMADEUS_API_KEY');
        $clientSecret = env('AMADEUS_API_SECRET');

        return Amadeus::builder($clientId, $clientSecret)->build();
    }
}
