<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Amadeus\Amadeus;
use App\Http\Controllers\Airline\AirportiatacodesController;
use App\Http\Controllers\Controller;
use App\Models\Airline\Airportiatacode;
use App\Services\AmadeusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $origin = $request->get('departure');
        $destination = $request->get('arrival');
        $adults = $request->get('noOfAdults', 1);
        $children = $request->get('noOfChilds', 0);
        $infants = $request->get('noOfInfants', 0);
        $cabinClass = $request->get('cabinClass', 'Y');
        try {
            $amadeus = $this->amadeus->getClient();
            $flightOffers = $amadeus->getShopping()->getFlightOffers()->get([
                "originLocationCode" => $origin,
                "destinationLocationCode" => $destination,
                "departureDate" => $departureDate,
                "adults" => $adults,
                "children" => $children,
                "infants" => $infants,
                // "travelClass" => $cabinClass, // Amadeus uses travelClass instead of cabinClass
                // "type" => "oneway",  // Ensure lowercase "oneway" for one-way trip
                // Optional: Comment out if unsure about fare type
                // "includeFares" => $fareType,
            ]);

            return $flightOffers[0]->getResponse()->getData();
            // return $flightOffers;
        } catch (\Amadeus\Exceptions\ResponseException $e) {
            // Handle API errors gracefully
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAvailability($request)
    {
        $origin = $request->get('departure');
        $destination = $request->get('arrival');
        $departureDate = $request->get('departDate'); // Assuming format matches Amadeus API requirements

        // try {
            $amadeus = $this->amadeus->getClient();
            $availability = $amadeus->getShopping()
                ->getAvailability()
                ->getFlightAvailabilities()
                ->post(json_encode([
                    'originDestinations' => [
                        [
                            // 'id' => 1, // Replace with actual ID if required by your library
                            'originLocationCode' => $origin,
                            'destinationLocationCode' => $destination,
                            'departureDateTime' => $departureDate,
                        ],
                    ],
                    'travelers' => [
                        [
                            'id' => 1,
                            'travelType' => 'ADULT',
                        ],
                    ],
                    'sources' => [
                        'INR', // Assuming GDS is required for your library (check documentation)
                    ],
                ]));

            // **Debugging:** Print the raw Amadeus API response for inspection
            echo "<pre>";
            print_r($availability);
            echo "</pre>";
            die(); // Comment out die() after debugging

            $amadeusResponse = json_decode($availability, true);

            // **Debugging:** Check if the price field exists
            if (isset($amadeusResponse['price'])) {
                $priceInEUR = $amadeusResponse['price']['total'];
                $conversionRate = 1.10; // EUR to USD conversion rate (replace with actual logic)
                $priceInUSD = $priceInEUR * $conversionRate;
                $formattedPriceUSD = number_format($priceInUSD, 2, '.', ',');
                return response()->json([
                    'availability' => $availability, // Original Amadeus response
                    'priceInUSD' => $formattedPriceUSD, // Converted price in USD
                ]);
            } else {
                // Handle the case where price information is missing
                return response()->json([
                    'message' => 'Price information not found in Amadeus response.',
                ]);
            }
        // } catch (\Amadeus\Exceptions\ResponseException $e) {
        //     $errorDetails = json_decode($e->getMessage(), true);
        //     return response()->json([
        //         'error' => $errorDetails['errors'][0]['title'] . ': ' . $errorDetails['errors'][0]['detail'],
        //         'error_code' => $errorDetails['errors'][0]['code'],
        //     ], $e->getCode());
        // }
    }

    public function Fare_MasterPricerTravelBoardSearch(Request $request)
    {

        $AirPortCodeController = new AirportiatacodesController;
        $travellers = ['noOfAdults' => $request['noOfAdults'], 'noOfChilds' => $request['noOfChilds'], 'noOfInfants' => $request['noOfInfants']];
        $departure = Airportiatacode::where("iata", $request['departure'])->first();
        $arrival = Airportiatacode::where("iata", $request['arrival'])->first();
        $session = Session::put('segments', [
            'departure' => $departure, 'arrival' => $arrival,
            'departDate' => $request['departDate'], 'returnDate' => $request['returnDate'],
            'triptype' => $request['trip-type'], 'cabinClass' => $request['cabinClass'],
            'traveller' => $travellers,
        ]);

        if ($request['trip-type'] === "oneway") {
            $dep = strip_tags(trim($AirPortCodeController->getCountry($request['departure'])));
            $ari = strip_tags(trim($AirPortCodeController->getCountry($request['arrival'])));

            if ($dep == "India" && $ari == "India") {
                $tripType = 1;
            } else {
                $tripType = 2;
            }
            // dd( $tripType);
            $oneways = $this->OnewayFlight($request);

            $availability = [];

            // return $oneways;
            // $availability = $this->getAvailability($request);

            // return $availability;

            // dd($availability);
            return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
            // return $data;
            // dd($data);
            // if (isset($data->response)) {
            //     $oneways = $data->response;
            // } else {
            //     $oneways = [];
            // }

            // $availability = $AuthenticateController->Availability($tripType, $request['trip-type'], $request['departDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));
            // dd( $availability);

            // dd($oneways, $availability ,$travellers);
            // if (isset($data->response)) {
            //     $oneways = $data->response;
            //     if ($data->status === Result::STATUS_OK ) {
            //         return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
            //     } elseif ($data->status != Result::STATUS_OK) {

            //         $oneways = [];

            //         return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
            //     } elseif ( $data->status === Result::STATUS_OK) {

            //         $availability = [];

            //         return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
            //     } else {
            //         return redirect()->route('no-flight')->with('message', 'Not available on this route Sagment.');
            //     }
            // } else {
            //     $oneways = [];
            //     // if ($availability["Status"] === "Success") {
            //     //     return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
            //     // } else {
            //     //     return redirect()->route('no-flight')->with('message', 'Not available on this route Sagment.');
            //     // }

            // }
        }
    }
}
