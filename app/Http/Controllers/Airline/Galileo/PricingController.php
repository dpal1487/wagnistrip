<?php

namespace App\Http\Controllers\Airline\Galileo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Airline\Galileo\AuthenticateController;

class PricingController extends Controller
{
    public function Pricing(Request $request)
    {
        // dd($request->all());
        
        $travellers = json_decode($request['travellers'], true);
        $SessionID = $request['SessionID'];
        $body = [
            "ClientCode"=> "MakeTrueTrip",
            "SessionID" => $request['SessionID'],
            "Key" => $request['Key'],
            "Pricingkey" => $request['Pricingkey'],
            "Provider" => $request['Provider'],
            "ResultIndex" => $request['ResultIndex'],
        ];
        
        $response = AuthenticateController::callApiWithHeadersGal("Pricing", $body);
        if ($response['Status'] != "Success") {
            return redirect()->route('error')->with('msg', 'Flight Unable to sell');
        }elseif ($request['trip'] == 'Roundtrip') {
        // dd($response);
            return view('flight-pages.roundtrip-flight-pages.internation-flight-pages.flight-gl-review', compact('response', 'travellers', 'SessionID'));
        }elseif ($request['trip'] == 'Oneway'){
            return view('flight-pages.oneway-flight-pages.flight-gl-review', compact('response', 'travellers', 'SessionID'));
        }
    }
}
