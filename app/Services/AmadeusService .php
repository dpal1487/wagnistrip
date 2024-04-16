<?php

namespace App\Services;

use Amadeus\Amadeus;

class AmadeusService
{
    private $amadeus;

    public function __construct(Amadeus $amadeus) // Or your custom service class
    {
        $this->amadeus = $amadeus;
    }

    public function searchFlights(array $params)
    {
        $response = $this->amadeus->getShopping()->getFlightOffers()->post($params);
        return json_decode($response->getBody(), true);
    }
}
