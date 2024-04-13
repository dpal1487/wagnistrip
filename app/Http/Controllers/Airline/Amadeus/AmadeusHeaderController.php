<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Amadeus\Client;
use Amadeus\Client\Params;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // Updated namespace
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AmadeusHeaderController extends Controller
{
    public function State($action)
    {

        try {
            $config = config('configuration.Amadeus');

            $client = new Client([
                'officeId' => Env::get('AMADEUS_OFFICE_ID'),
                'userId' => Env::get('AMADEUS_USER_ID'),
                'passwordData' => base64_encode(Env::get('AMADEUS_PASSWORD')),
                'soapHeaderVersion' => Client::HEADER_V4,
                'wsdl' => $config['wsdl_path'],
                'stateful' => 'New', // Or 'Existing' based on your needs
                'logger' => Log::channel('amadeus'), // Optional logging
            ]);

            // Use the $client object for Amadeus API calls in this or other methods

            return response()->json(['message' => 'Amadeus client created successfully']);
        } catch (Exception $e) {
            Log::error('Amadeus client creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Amadeus client creation error',
                'error' => $e->getMessage(),
            ], 500);
        }

        // $config = Config::get('configuration.Amadeus');
        // // return $config;

        // $logName = 'requestResponse';
        // $logSrc = 'logs/';
        // $log = new Logger('log');
        // $log->pushHandler(new StreamHandler($logSrc . $logName . '.log', Logger::INFO));

        // $wsdlPath = env('AMADEUS_WSDL_PATH'); // Replace with the actual WSDL path

        // $response = new Params([
        //     'authParams' => [
        //         'officeId' => env('AMADEUS_OFFICE_ID'),
        //         'userId' => env('AMADEUS_USER_ID'),
        //         'passwordData' => base64_encode(env('AMADEUS_PASSWORD')),
        //     ],
        //     'sessionHandlerParams' => [
        //         'soapHeaderVersion' => Client::HEADER_V4,
        //         'wsdl' => $wsdlPath,
        //         'stateful' => $action,
        //         'logger' => Log::channel('amadeus'),
        //     ],
        // ]);

        // return $response;
    }
}
