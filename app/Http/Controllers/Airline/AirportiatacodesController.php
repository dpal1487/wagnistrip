<?php

namespace App\Http\Controllers\Airline;

use App\Models\Airline\Airportiatacode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;


class AirportiatacodesController extends Controller
{
    public function searchAirIataCode(Request $request)
    {

      
        
        $search = $request['search'];

        

        if ($search == '') {
            $employees = Airportiatacode::orderby('updated_at', 'DESC')->select('id', 'city', 'iata', 'country', 'airport')->limit(10)->get();
           
        } else {
            
            $employees = Airportiatacode::where('iata', 'like','%'. $search.'%')
                 ->orWhere('city', 'like', $search .'%')
                 ->limit(10)->get();
        }
    
        $response = array();
        foreach ($employees as $employee) {
            $response[] = array(
                "id" => $employee['iata'],
                "text" => $employee['city'] . " (" . $employee['iata'] . ") " . $employee['country']."\r\n".$employee['airport']
              
            );
            
        }

        echo json_encode($response);
        exit;
    }
    
    public function searchCountryCode(Request $request)
    {
        $search = $request['search'];

        if ($search == '') {
            $countries = Country::orderby('updated_at', 'DESC')->select('id', 'country', 'code')->limit(5)->get();
        } else {

            $countries = Country::where('country', 'like','%'. $search.'%')
                 ->orWhere('code', 'like', $search .'%')
                 ->limit(5)->get();

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
            $countries = Country::orderby('updated_at', 'DESC')->select('id', 'country', 'iso_two')->limit(5)->get();
        } else {

            $countries = Country::where('country', 'like','%'. $search.'%')
                 ->orWhere('iso_two', 'like', $search .'%')
                 ->limit(5)->get();

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
        $res = $datas['country'];

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
    public static function CityNamesLists(Request $request)
    {
        $search = $request['search'];

        

        if ($search == '') {
            $employees = Airportiatacode::orderby('updated_at', 'DESC')->select('id', 'city', 'iata', 'country', 'airport')->limit(10)->get();
           
        } else {
            
            $employees = Airportiatacode::where('iata', 'like','%'. $search.'%')
                 ->orWhere('city', 'like', $search .'%')
                 ->limit(10)->get();
        }
    
        $response = array();
        foreach ($employees as $employee) {
            $response[] = array(
                "id" => $employee['iata'],
                "text" => $employee['city'] . " (" . $employee['iata'] . ") " . $employee['country']."\r\n".$employee['airport']
              
            );
            
        }

        echo json_encode($response);
        exit;
   }
   
   
}
