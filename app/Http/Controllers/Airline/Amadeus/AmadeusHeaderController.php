<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Http\Controllers\Controller;
use Amadeus\Client;
use Amadeus\Client\Params; // Updated namespace
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AmadeusHeaderController extends Controller {
    public function State($action) {

        $config = Config::get('configuration.Amadeus');
        // return $config;

        $logName = 'requestResponse';
        $logSrc = 'logs/';
        $log = new Logger('log');
        $log->pushHandler(new StreamHandler($logSrc . $logName .'.log', Logger::INFO));

      
        $wsdlPath =  env('AMADEUS_WSDL_PATH'); // Replace with the actual WSDL path

        $response = new Params([
            'authParams' => [
                'officeId' => env('AMADEUS_OFFICE_ID'),
                'userId' => env('AMADEUS_USER_ID'),
                'passwordData' => base64_encode(env('AMADEUS_PASSWORD')),
            ],
            'sessionHandlerParams' => [
                'soapHeaderVersion' => Client::HEADER_V4,
                'wsdl' => $wsdlPath,
                'stateful' => $action,
                'logger' => Log::channel('amadeus'),
            ],
        ]);

        return $response;
    }
}