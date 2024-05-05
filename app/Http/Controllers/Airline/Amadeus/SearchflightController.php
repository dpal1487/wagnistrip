<?php

namespace App\Http\Controllers\Airline\Amadeus;

use App\Http\Controllers\Airline\AirPortIATACodesController;
use App\Http\Controllers\Airline\Galileo\AuthenticateController;
use App\Http\Controllers\Controller;
use App\Models\VisitorGeolocation;
use App\Services\AmadeusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SearchflightController extends Controller
{
    private $amadeus;
    public function __construct(AmadeusService $amadeus) // Or your custom service class
    {
        $this->amadeus = $amadeus;
    }

    public function onewayFlight($request, $departureDate, $origin, $destination, $adults, $children, $infants, $travelClass, $passengerType)
    {

        try {
            $amadeus = $this->amadeus->getClient();
            $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
                "originLocationCode" => $origin,
                "destinationLocationCode" => $destination,
                "departureDate" => $departureDate,
                "adults" => $adults,
                // "nonStop" => true,
                // "includedAirlineCodes" =>
                // "excludedAirlineCodes" =>
                // "maxPrice" =>
                // "max" => 6,
                "currencyCode" => 'INR',
                "children" => $children,
                "infants" => $infants,
                "travelClass" => $travelClass, // Amadeus uses travelClass  Available values : ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST
                // Optional: Comment out if unsure about fare type
                // "includeFares" => $fareType,
            ]);
            // return $amadeus->getShopping()->getFlightOffers()->getPricing()->postWithFlightOffers($flightOffers)->toArray();

            return $flightOffers[0]->getResponse()->getBodyAsJsonObject();
            // return $flightOffers;
        } catch (\Amadeus\Exceptions\ResponseException $e) {
            // Handle API errors gracefully
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function roundTripFlight($request, $departureDate, $returnDate, $origin, $destination, $adults, $children, $infants, $travelClass, $passengerType)
    {
        $currencyconversion = VisitorGeolocation::geolocationInfo();

        return json_incode($currencyconversion);
        try {
            $amadeus = $this->amadeus->getClient();
            $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
                "originLocationCode" => $origin,
                "destinationLocationCode" => $destination,
                "departureDate" => $departureDate,
                "adults" => $adults,
                'returnDate' => $returnDate,
                "max" => 10,
                "children" => $children,
                    // "includedAirlineCodes" =>
                    // "excludedAirlineCodes" =>
                    // "maxPrice" =>
                // "nonStop" => true,
                "infants" => $infants,
                "currencyCode" => 'INR',
                "travelClass" => $travelClass, // Amadeus uses travelClass Available values : ECONOMY, PREMIUM_ECONOMY, BUSINESS, FIRST
                // Optional: Comment out if unsure about fare type
                // "includeFares" => true,  // Include fares for sorting (optional)
                // "maxNumberOfStops" => 2,  // Retrieve flights with up to 2 connections
                // 'fare' => 'STD',
            ]);
            // $pricing = $amadeus->getShopping()->getFlightOffers()->getPricing()->postWithFlightOffers($flightOffers);
            $flightOffers = $flightOffers[0]->getResponse()->getBodyAsJsonObject();
            // return $flightOffers->data;
            return response()->json($flightOffers);
        } catch (\Amadeus\Exceptions\ResponseException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function isDefenseFareAvailable($flightOffer, $origin, $destination, $travelDate)
    {
        // Implement logic to check if defense fare is available for this flight
        // This might involve checking a list of airlines or using an external API (if available)
        // Example using an airline list (replace with your actual logic)
        $defenseAirlines = ['AA', 'UA', 'DL', 'AI']; // Placeholder list of airlines offering defense fares
        $airlineCode = $flightOffer->validatingAirlineCodes[0];

        return in_array($airlineCode, $defenseAirlines);

    }

    public function Fare_MasterPricerTravelBoardSearch(Request $request)
    {
        $departureDate = $request->get('departDate');
        $returnDate = $request->get('returnDate');
        $origin = $request->get('departure');
        $destination = $request->get('arrival');
        $adults = $request->get('noOfAdults', 1);
        $children = $request->get('noOfChilds', 0);
        $infants = $request->get('noOfInfants', 0);
        $travelClass = request()->input('cabinClass');
        $passengerType = $request->get('fare'); // Get user selected passenger type
        $airPortCodeController = new AirPortIATACodesController;
        $dep = strip_tags(trim($airPortCodeController->getCountry($request['departure'])));
        $ari = strip_tags(trim($airPortCodeController->getCountry($request['arrival'])));
        if ($dep == "India" && $ari == "India") {
            $tripType = 1;
        } else {
            $tripType = 2;
        }
        switch (strtoupper($travelClass)) {
            case 'Y':
                $travelClass = 'ECONOMY';
                break;
            case 'W':
                $travelClass = 'PREMIUM_ECONOMY';
                break;
            case 'C':
                $travelClass = 'BUSINESS';
                break;
            case 'F':
                $travelClass = 'FIRST';
                break;
            default:
                return response()->json(['error' => 'Invalid cabin class provided. Please choose ECONOMY, BUSINESS, or FIRST_CLASS.'], 400);
        }
        // trip type  None = 0,  OneWay= 1, RoundTrip = 2, MultiCity = 3, SpecialReturn = 4,
        // trip == Domestic = 1, InterNational = 2, none = 0,
        $authenticateController = new AuthenticateController;
        $travellers = ['noOfAdults' => $adults, 'noOfChilds' => $children, 'noOfInfants' => $infants];
        $currency_conversion = $this->getvisitorcountrycurrency();
        $availability = [];
        if ($request['trip-type'] === "oneway") {
            $oneways = $this->onewayFlight($request ,$departureDate, $origin, $destination, $adults, $children, $infants, $travelClass, $passengerType);
            $availability = $authenticateController->Availability($tripType, $request['trip-type'], $request['departDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']), $request['fare']);
        } else if ($request['trip-type'] === "roundtrip") {
            $oneways = $this->roundTripFlight($request, $departureDate, $returnDate, $origin, $destination, $adults, $children, $infants, $travelClass, $passengerType);
            $availability = $authenticateController->AvailabilityRound($tripType, $request['trip-type'], $request['departDate'], $request['returnDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']), $request['fare']);


        }
        return $oneways;
        return $availability;
        // return response()->json(['galileo' => $availability , 'amadeus' => $oneways]);
        // return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
    }

    public static function getvisitorcountrycurrency()
    {
        $session = !empty(Session::get('currency')) ? Session::get('currency') : '';
        if (empty($session)) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $loc = Http::get('http://api.ipstack.com/' . $ip . '?access_key=528d7ed0b65ea7d1f694af15b0ced1a4')->json();
            $cncode = 'IN';
            if (!empty($loc)) {
                if (isset($loc['country_code'])) {
                    $cncode = $loc['country_code'];
                }
            }
            if ($cncode == 'US') {
                $currency = 'USD';
                $currency_symbol = '$';
            } else if ($cncode == 'IN') {
                $currency = 'INR';
                $currency_symbol = '₹';
            } else {
                $currency = DB::table('country_currency')
                    ->where('country', $cncode)
                    ->first();
                $currency = $currency->currency;
                $currency_symbol = $currency->symbol;
            }
            Session::forget('currency');
            Session::push('currency', $currency);
            Session::forget('currency_symbol');
            Session::push('currency_symbol', $currency_symbol);
            return $currency;
        } else {
            $currency = 'INR';
            if (!empty($session)) {
                $key_cn = $session;
                $currency = ($key_cn) ? $key_cn[0] : $key_cn;
            } else {
                $currency = 'INR';
            }
            return $currency;
        }
    }

}
