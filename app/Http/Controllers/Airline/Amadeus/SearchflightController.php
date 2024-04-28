<?php

namespace App\Http\Controllers\Airline\Amadeus;

use App\Http\Controllers\Airline\AirportiatacodesController;
use App\Http\Controllers\Airline\Galileo\AuthenticateController;
use App\Http\Controllers\Controller;
use App\Models\Airline\Airportiatacode;
use App\Services\AmadeusService;
use Illuminate\Http\Request;

// use App\Http\Controllers\searchAirIataCode;

// ------- Calendar --------------------------------

class SearchflightController extends Controller
{
    private $amadeus;

    public function __construct(AmadeusService $amadeus) // Or your custom service class
    {
        $this->amadeus = $amadeus;
    }

    public function OnewayFlight($request)
    {
        $departure = Airportiatacode::where("iata", $request['departure'])->first();
        $departureDate = $request->get('departDate');
        $returnDate = $request->get('returnDate');
        $origin = $request->get('departure');
        $destination = $request->get('arrival');
        $adults = $request->get('noOfAdults', 1);
        $children = $request->get('noOfChilds', 0);
        $infants = $request->get('noOfInfants', 0);
        $travelClass = request()->input('cabinClass', 'ECONOMY');
        $passengerType = $request->get('fare'); // Get user selected passenger type
        try {
            $amadeus = $this->amadeus->getClient();
            if ($request['trip-type'] === "oneway") {

                $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
                    "originLocationCode" => $origin,
                    "destinationLocationCode" => $destination,
                    "departureDate" => $departureDate,
                    "adults" => $adults,
                    // "max" => 6,
                    "children" => $children,
                    "infants" => $infants,
                    "travelClass" => $travelClass, // Amadeus uses travelClass instead of cabinClass
                    // "type" => "oneway",  // Ensure lowercase "oneway" for one-way trip
                    // Optional: Comment out if unsure about fare type
                    // "includeFares" => $fareType,
                ]);
            } else if ($request['trip-type'] === "roundtrip") {
                $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
                    "originLocationCode" => $origin,
                    "destinationLocationCode" => $destination,
                    "departureDate" => $departureDate,
                    "adults" => $adults,
                    'returnDate' => $returnDate,
                    "max" => 6,
                    "children" => $children,
                    "infants" => $infants,
                    "travelClass" => $travelClass, // Amadeus uses travelClass instead of cabinClass
                    // "type" => "oneway",  // Ensure lowercase "oneway" for one-way trip
                    // Optional: Comment out if unsure about fare type
                    // "includeFares" => $fareType,
                ]);
            }
            // return $amadeus->getShopping()->getFlightOffers()->getPricing()->postWithFlightOffers($flightOffers)->toArray();

            return $flightOffers[0]->getResponse()->getBodyAsJsonObject();
            // return $flightOffers;
        } catch (\Amadeus\Exceptions\ResponseException $e) {
            // Handle API errors gracefully
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function Fare_MasterPricerTravelBoardSearch(Request $request)
    {
        $AirPortCodeController = new AirportiatacodesController;
        $AuthenticateController = new AuthenticateController;
        $travellers = ['noOfAdults' => $request['noOfAdults'], 'noOfChilds' => $request['noOfChilds'], 'noOfInfants' => $request['noOfInfants']];

        // if ($request['trip-type'] === "oneway") {
        $dep = strip_tags(trim($AirPortCodeController->getCountry($request['departure'])));
        $ari = strip_tags(trim($AirPortCodeController->getCountry($request['arrival'])));

        if ($dep == "India" && $ari == "India") {
            $tripType = 1;
        } else {
            $tripType = 2;
        }
        // $oneways = $this->OnewayFlight($request);

        // return $oneways;

        $availability = [];
        if ($request['trip-type'] === "oneway") {
            $availability = $AuthenticateController->Availability($tripType, $request['trip-type'], $request['departDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));
        } else if ($request['trip-type'] === "roundtrip") {
            $availability = $AuthenticateController->AvailabilityRound($tripType, $request['trip-type'], $request['departDate'], $request['returnDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));
        }
        return $availability;
        return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
    }
}
