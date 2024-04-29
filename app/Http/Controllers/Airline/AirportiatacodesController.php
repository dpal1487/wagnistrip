<?php

namespace App\Http\Controllers\Airline;

use App\Models\Airline\Airportiatacode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Hotel_City;

class AirPortIATACodesController extends Controller
{

    public function searchAirIataCode(Request $request)
    {
        $search = $request['search'];
        if ($search == '') {
            $employees = Airportiatacode::orderby('iata', 'ASC')->select('id' , 'iata'  , 'city', 'country' ,'airport'  , 'CityCode', 'CountryCode')->limit(10)->get();
        } else {
            $employees = Airportiatacode::orderby('iata', 'ASC')->where('CountryCode', 'like', $search )
                 ->orWhere('city', 'like', $search.'%')
                 ->orWhere('country', 'like',$search.'%')
                 ->orWhere('airport', 'like', $search.'%')
                 ->orWhere('CityCode', 'like', $search.'%')
                 ->orWhere('iata', 'like', $search.'%')
                 ->limit(20)->get();
        }
        $response = array();
        foreach ($employees as $employee) {
            $response[] = array(
                "id" => $employee['iata'],
                "text" => $employee['iata']."\n".$employee['country']."\n".$employee['airport']. "\n".$employee['city'],
                "head" => $employee['city'] .  "",
            );
        }
        echo json_encode($response);
        exit;
    }

    public function searchCountryCode(Request $request)
    {
        $search = $request['search'];

        if ($search == '') {
            $countries = Country::orderby('updated_at', 'DESC')->select('id', 'country', 'code')->limit(10)->get();
        } else {

            $countries = Country::where('country', 'like','%'. $search.'%')
                 ->orWhere('code', 'like', $search .'%')
                 ->limit(10)->get();
        }
        $response = array();
        foreach ($countries as $employee) {
            $response[] = array(
                "id" => $employee['code'],
                "text" => $employee['country']." (" . $employee['code'] . ") ",
            );
        }

        echo json_encode($response);
        exit;
    }

    public function searchCountryIso(Request $request)
    {
        $search = $request['search'];
        if ($search == '') {
            $countries = Country::orderby('updated_at', 'DESC')->select('id', 'country', 'iso_two')->limit(10)->get();
        } else {
            $countries = Country::where('country', 'like','%'. $search.'%')
                 ->orWhere('iso_two', 'like', $search .'%')
                 ->limit(10)->get();
        }
        $response = array();
        foreach ($countries as $employee) {
            $response[] = array(
                "id" => $employee['iso_two'],
                "text" => $employee['country']." (" . $employee['iso_two'] . ") ",
            );
        }
        echo json_encode($response);
        exit;
    }
    public static function getCity($d)
    {
        $res = '';
        $datas = Airportiatacode::where('iata', $d)->get('city');
        foreach ($datas as $data) {
            $res = $data['city'];
        }
        if ($res != '') {
            return $res;
        }
        return $d;
    }

    public static function getCountry($d)
    {
        $datas = Airportiatacode::where('iata', $d)->first('country');
        $res = '';
        $res = $datas['country']??'';
        if (!isset($res) && empty($res)) {
            return $d;
        }
        return $res;
    }

    public static function getAirport($d)
    {
        $res = '';
        $datas = Airportiatacode::where('iata', $d)->get('airport');
        foreach ($datas as $data) {
            $res = $data['airport'];
        }
        if ($res != '') {
            return $res;
        }
        return $d;
   }

    public static function HotelCity(Request $request)
    {
        $search = $request['search'];
        // dd($request->all());
        if ($search == '') {
            $employees = Hotel_City::orderby('city', 'ASC')->select('id' , 'city', 'state' ,'country')->limit(10)->get();
        } else {
            $employees = Hotel_City::orderby('city', 'ASC')->where('city', 'like', $search )
                //  ->orWhere('city', 'like', $search.'%')
                 ->orWhere('state', 'like',$search.'%')
                 ->orWhere('country', 'like', $search.'%')
                //  ->orWhere('CityCode', 'like', $search.'%')
                 ->orWhere('city', 'like', $search.'%')
                 ->limit(20)->get();
        }
        $response = array();
        foreach ($employees as $employee) {
            $response[] = array(
                "id" => $employee['city'],
                "text" => $employee['city'] . "\n (" . $employee['city'] . ") " . $employee['country']."\n",
                "head" => $employee['city'] . "\n " . $employee['country'] . "",
            );
        }
        echo json_encode($response);
        exit;
   }
}
