<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Amadeus\Amadeus;
use Amadeus\Client;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\Result;
use App\Http\Controllers\Airline\AirportiatacodesController;
use App\Http\Controllers\Airline\Amadeus\AmadeusHeaderController;
use App\Http\Controllers\Airline\Galileo\AuthenticateController;
use App\Http\Controllers\Controller;
use App\Models\Airline\Airportiatacode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// use App\Http\Controllers\searchAirIataCode;

// ------- Calendar --------------------------------


class SearchflightController extends Controller
{
    private $amadeus;

    public function __construct(Amadeus $amadeus) // Or your custom service class
    {
        $this->amadeus = $amadeus;
    }


    public function OnewayFlight($reqDeparture, $reqArrival, $reqDepartDate) {
        $HeaderController = new AmadeusHeaderController;
        $params = $HeaderController->State(false);
        $client = new Client($params);

        $body = new FareMasterPricerTbSearch([
            'nrOfRequestedResults' => 200,
            'nrOfRequestedPassengers' => 1,
            'passengers' => [
                new MPPassenger([
                    'type' => MPPassenger::TYPE_ADULT,
                    'count' => 1,
                ]),
            ],
            'itinerary' => [
                new MPItinerary([
                    'departureLocation' => new MPLocation(['city' => $reqDeparture]),
                    'arrivalLocation' => new MPLocation(['city' => $reqArrival]),
                    'date' => new MPDate([
                        'dateTime' => new \DateTime($reqDepartDate, new \DateTimeZone('UTC')),
                    ]),
                    'airlineOptions' => [
                        MPItinerary::AIRLINEOPT_EXCLUDED => ['H1'],
                    ],
                ]),
            ],
        ]);

        $data = $client->fareMasterPricerTravelBoardSearch($body);
        // dd($data);
        if ($data->status === Result::STATUS_OK) {
            return $data;
        } else {
            return redirect()->route('no-flight')->with('message', 'Not Ablable on this route Sagment.');
        }
    }

    public function Fare_MasterPricerTravelBoardSearch(Request $request)
    {

    $departureDate = $request->get('departDate'); // Get parameters from request
    $origin = $request->get('departure');
    $destination = $request->get('arrival');
    $adults = $request->get('noOfAdults', 1);
    $children = $request->get('noOfChilds', 0);
    $infants = $request->get('noOfInfants', 0);
    $cabinClass = $request->get('cabinClass', 'Y');
    $fareType = $request->get('fare', 'Regular+Fares'); // Assuming 'Regular+Fares' is a valid Amadeus fare type

    // return $fareType;
    try {
        $flightOffers = $this->amadeus->getShopping()->getFlightOffers()->get([
            "originLocationCode" => $origin,
            "destinationLocationCode" => $destination,
            "departureDate" => $departureDate,
            "adults" => $adults,
            "children" => $children,
            "infants" => $infants,
            "travelClass" => $cabinClass, // Amadeus uses travelClass instead of cabinClass
            "type" => "oneway", // Specify oneway trip type
            "includeFares" => $fareType // Include the desired fare type
        ]);

        return $flightOffers;

        // Process the flight offers response (e.g., display results, store data)
        // ... (your logic to handle successful response)

    } catch (\Amadeus\Exceptions\ResponseException $e) {
        // Handle API errors gracefully
        return response()->json(['error' => $e->getMessage()], 500);
    }

        // return $request;
        // $flightOffers = $this->amadeus->getShopping()->getFlightOffers()->get(["originLocationCode"=>"SYD", "destinationLocationCode"=>"BKK", "departureDate"=>"2024-11-01", "adults"=>1, "max"=>6]);
        // return $flightOffers;
        try {
            $response = $this->amadeus->getShopping()->getFlightOffers()->get([
                "originLocationCode" => $request->departure,
                "destinationLocationCode" => $request->arrival,
                "departureDate" => $request->departDate,
                "adults" => $request->noOfAdults,
            ]);
            return response()->json($flightOffers);// Process flight data (implementation omitted)
            // Return processed flight data
        } catch (Exception $e) {
            throw new Exception("Amadeus API error: " . $e->getMessage());
        }

        // $amadeus = app()->make(Amadeus::class); // Using service provider

        $flightOffers = $this->amadeus->getShopping()->getFlightOffers()->get([
            "originLocationCode" => $request->departure,
            "destinationLocationCode" => $request->arrival,
            "departureDate" => $request->departDate,
            "adults" => $request->noOfAdults,
        ]);

        return response()->json($flightOffers);
        // return $amadeus->getAirport()->getDirectDestinations()->get(["departureAirportCode" => "DEL"]);
        // return  $flightOffers->getResponse()->getResult();


        $AuthenticateController = new AuthenticateController;

        $AirPortCodeController = new AirportiatacodesController;

        $HeaderController = new AmadeusHeaderController;

        $params = $HeaderController->State(false);
        $client = new Client($params);


        $travellers = ['noOfAdults' => $request['noOfAdults'], 'noOfChilds' => $request['noOfChilds'], 'noOfInfants' => $request['noOfInfants']];
        $departure = Airportiatacode::where("iata", $request['departure'])->first();
        $arrival = Airportiatacode::where("iata", $request['arrival'])->first();
        Session::put('segments', [
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
            $data = $this->OnewayFlight($request['departure'], $request['arrival'], $request['departDate']);
            // dd($data);
            if(isset($data->response)){
                $oneways = $data->response;
            }else{
                $oneways = [];
            }

            $availability = $AuthenticateController->Availability($tripType, $request['trip-type'], $request['departDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));
            // dd( $availability);

            // dd($oneways, $availability ,$travellers);
            if(isset($data->response)){
                $oneways = $data->response;
                if ($data->status === Result::STATUS_OK && $availability["Status"] === "Success") {
                    return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
                } elseif ($data->status != Result::STATUS_OK && $availability["Status"] === "Success") {

                    $oneways = [];

                    return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
                } elseif ($availability["Status"] != "Success" && $data->status === Result::STATUS_OK) {

                    $availability = [];

                    return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
                } else {
                    return redirect()->route('no-flight')->with('message', 'Not available on this route Sagment.');
                }
            }else{
                $oneways = [];
                if ( $availability["Status"] === "Success") {
                    return view('flight-pages.oneway-flight-pages.flight-search', compact('oneways', 'travellers', 'availability'));
                }  else {
                    return redirect()->route('no-flight')->with('message', 'Not available on this route Sagment.');
                }

            }
        } elseif ($request['trip-type'] === "roundtrip") {
            $opt = new FareMasterPricerTbSearch([
                'nrOfRequestedResults' => 200,
                'nrOfRequestedPassengers' => 1,
                'passengers' => [
                    new MPPassenger([
                        'type' => MPPassenger::TYPE_ADULT,
                        'count' => 1,
                    ]),
                ],
                'itinerary' => [
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => $request['departure']]),
                        'arrivalLocation' => new MPLocation(['city' => $request['arrival']]),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime($request['departDate'], new \DateTimeZone('UTC')),
                        ]),
                    ]),
                    new MPItinerary([
                        'departureLocation' => new MPLocation(['city' => $request['arrival']]),
                        'arrivalLocation' => new MPLocation(['city' => $request['departure']]),
                        'date' => new MPDate([
                            'dateTime' => new \DateTime($request['returnDate'], new \DateTimeZone('UTC')),
                        ]),
                    ]),
                ],
            ]);

            if ($AirPortCodeController->getCountry($request['departure']) == "India" && $AirPortCodeController->getCountry($request['arrival']) == "India") {

                $availabilityOutbounds = $AuthenticateController->Availability(1, 'oneway', $request['departDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));

                $availabilityInbounds = $AuthenticateController->Availability(1, 'oneway', $request['returnDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['arrival'], $request['departure'], ucfirst($request['cabinClass']));

                $roundtripOutbounds = $this->OnewayFlight($request['departure'], $request['arrival'], $request['departDate']);

                $roundtripInbounds = $this->OnewayFlight($request['arrival'], $request['departure'], $request['returnDate']);

                $roundtripdomestic = $client->fareMasterPricerTravelBoardSearch($opt);
                $availabilitys = $AuthenticateController->AvailabilityRound(1, 'roundtrip', $request['departDate'], $request['returnDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], ucfirst($request['cabinClass']));
                // dd( $roundtripdomestic ,  $availabilitys,["gail" => [$availabilityOutbounds, $availabilityInbounds], "amd" => [$roundtripOutbounds, $roundtripInbounds]]);
                if ($roundtripdomestic->status === Result::STATUS_OK) {
                    $roundtrips = $roundtripdomestic->response;
                    return view('flight-pages.roundtrip-flight-pages.domestic-flight-pages.flight-search', compact('roundtripOutbounds', 'roundtripInbounds', 'travellers', 'availabilityOutbounds', 'availabilityInbounds'));
                } elseif (($roundtripdomestic->status == "ERR") && ( $availabilitys['Status'] == "Success")) {
                    return view('flight-pages.roundtrip-flight-pages.domestic-flight-pages.flight-search', compact('roundtripOutbounds', 'roundtripInbounds', 'travellers', 'availabilityOutbounds', 'availabilityInbounds'));
                    // return redirect()->route('no-flight')->with('message', 'No Available on this route Sagment.');

                } else {
                    return redirect()->route('no-flight')->with('message', 'No Available on this route Sagment.');
                }
            } else {

                $roundtripInternational = $client->fareMasterPricerTravelBoardSearch($opt);

                $availabilitys = $AuthenticateController->AvailabilityRound(2, 'roundtrip', $request['departDate'], $request['returnDate'], $request['noOfAdults'], $request['noOfChilds'], $request['noOfInfants'], $request['departure'], $request['arrival'], $request['cabinClass']);

                    // dd($roundtripInternational , $availabilitys);
                if ($roundtripInternational->status === Result::STATUS_OK) {

                    $roundtrips = $roundtripInternational->response;
                    return view('flight-pages.roundtrip-flight-pages.internation-flight-pages.flight-search', compact('roundtrips', 'travellers', 'availabilitys'));
                } else {
                    return redirect()->route('no-flight')->with('message', 'No Available on this route Sagment.');
                }
            }
        }
    }
}
