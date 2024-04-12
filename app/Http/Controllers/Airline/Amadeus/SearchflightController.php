<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Amadeus\Amadeus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchflightController extends Controller
{
    public function Fare_MasterPricerTravelBoardSearch(Request $request)
    {
        $amadeus = app()->make(Amadeus::class); // Using service provider

        // Example flight search using Amadeus\Shopping\FlightOffers
        $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
            "originLocationCode" => "MAD",
            "destinationLocationCode" => "NYC",
            "departureDate" => "2024-12-01",
            "adults" => 1,
        ]);

        $responseBody = $flightOffers[0]->getResponse()->getResult();

        return response()->json($responseBody);

        // Process the API response (flightOffers)
        return view('flight_results', compact('flightOffers'));
    }
}
