<?php

namespace App\Http\Controllers\Airline\Galileo;

use App\Http\Controllers\Airline\Galileo\AuthenticateController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\GalileoFlightLog;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

class TicketingController extends Controller
{

    public function Ticketing(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }

        $bookingData = Cart::where('uniqueid', $input['uniqueid'])->first();

        $getsession = json_decode($bookingData['getsession'], true);

        $AddPassengerDetailsBody = [
            "SessionID" => $getsession['SessionID'],
            "Key" => $getsession['Key'],
            "ReferenceNo" => $getsession['ReferenceNo'],
            "CustomerInfo" => [
                "Email" => $bookingData['email'],
                "Mobile" => $bookingData['phonenumber'],
                "Address" => "5b 13 Street No 1 Subhash Nagar Metro Gate No 1",
                "City" => "Delhi",
                "State" => "Delhi",
                "CountryCode" => "IN",
                "CountryName" => "India",
                "ZipCode" => "110018",
                "PassengerDetails" => json_decode($bookingData['travllername'], true),
                "PassengerTicketDetails" => [],
                "Payment" => (object) [],
            ],
            "SSRInfo" => [],
            "TotalAmount" => "0",
            "SSRAmount" => 0,
            "Discount" => 0,
            "GrandTotalFare" => "0",
            "IsGSTProvided" => false,
        ];

        $AddPassengerDetails = AuthenticateController::callApiWithHeadersGal("AddPassengerDetails", $AddPassengerDetailsBody);

        if ($AddPassengerDetails['Status'] == "Success") {

            $BookingBody = [
                "SessionID" => $getsession['SessionID'],
                "Key" => $AddPassengerDetails['Key'],
                "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                "Provider" => $getsession['Provider'],
            ];

            $Booking = AuthenticateController::callApiWithHeadersGal("Booking", $BookingBody);
            if ($Booking['Status'] == "Hold") {

                $TicketBody = [
                    "SessionID" => $getsession['SessionID'],
                    "Key" => $AddPassengerDetails['Key'],
                    "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                    "Provider" => $getsession['Provider'],
                ];

                $Ticket = AuthenticateController::callApiWithHeadersGal("Ticket", $TicketBody);

                if ($Ticket['Status'] == "Success") {

                    $getBookingBody = [
                        "SessionID" => $getsession['SessionID'],
                        "Key" => $AddPassengerDetails['Key'],
                        "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                        "PnrNo" => "",
                        "Provider" => $getsession['Provider'],
                        "FirstName" => "",
                        "LastName" => "",
                        "From" => "",
                        "To" => "",
                    ];

                    $getBooking = AuthenticateController::callApiWithHeadersGal("GetBookingDetails", $getBookingBody);

                    if ($getBooking['Status'] == "Success") {

                        $saveBooking = new Booking;
                        $logs_id = GalileoFlightLog::where('session_id', '=', $getsession['SessionID'])->first('id');
                        $userId = User::where('phone', $bookingData['phonenumber'])->orWhere('email', $bookingData['email'])->first('id');

                        if (empty($userId->id)) {
                            $user = new User;
                            $user->name = "Enter Full Name";
                            $user->email = strtolower($bookingData['email']);
                            $user->phone = $bookingData['phonenumber'];
                            $user->password = Hash::make("Make@123");
                            $user->save();
                            $userId = $user->id;
                        } else {
                            $userId = $userId->id;
                        }

                        $saveBooking->booking_from = "GALILEO";
                        $saveBooking->booking_id = $input['uniqueid'];
                        $saveBooking->trip = $getBooking['AirBookingResponse'][0]['Trip'];
                        $saveBooking->trip_type = 2;
                        $saveBooking->trip_stop = "0-Stop";
                        $saveBooking->gds_pnr = $getBooking['AirBookingResponse'][0]['PNR'];
                        $saveBooking->airline_pnr = $getBooking['AirBookingResponse'][0]['FlightDetails'][0]['AirLinePNR'];
                        $saveBooking->email = $getBooking['AirBookingResponse'][0]['CustomerInfo']['Email'];
                        $saveBooking->mobile = $getBooking['AirBookingResponse'][0]['CustomerInfo']['Mobile'];
                        $saveBooking->itinerary = json_encode($getBooking['AirBookingResponse'][0]['FlightDetails'], true);
                        $saveBooking->baggage = json_encode($getBooking['AirBookingResponse'][0]['PaxBaggageDetails'], true);
                        $saveBooking->passenger = json_encode($getBooking['AirBookingResponse'][0]['CustomerInfo']['PassengerTicketDetails'], true);
                        $saveBooking->fare = json_encode($getBooking['AirBookingResponse'][0]['FareDetails'], true);
                        $saveBooking->status = $getBooking['AirBookingResponse'][0]['BookingStatus'];
                        $saveBooking->logs_id = $logs_id->id;
                        $saveBooking->user_id = $userId;
                        $saveBooking->save();

                        return view('flight-pages.booking-confirm.oneway-gal-flight-booking-confirm')->with('bookings', $saveBooking);
                    }
                
                } else {

                    return redirect()->route('error')->with('msg', "Your Booking Has Been Faild!");
                }

            } else {

                return redirect()->route('error')->with('msg', "Your Ticket Booking Faild. Kindly contact on this number 7669988012 for further concern.");
            }

        } else {

            return redirect()->route('error')->with('msg', "Passenger Data Invalid. Kindly contact on this number 7669988012 for further concern.");
        }

    }

    public function DomGalBooking(Request $request)
    {

        $input = $request->all();
        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        // $payment = $api->payment->fetch($input['razorpay_payment_id']);

        // if (count($input) && !empty($input['razorpay_payment_id'])) {
        //     try {
        //         $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

        //     } catch (Exception $e) {
        //         return $e->getMessage();
        //         Session::put('error', $e->getMessage());
        //         return redirect()->back();
        //     }
        // }

        $bookingData = Cart::where('uniqueid', $input['tnxid'])->first();
        $getsessions = json_decode($bookingData['getsession'], true);

        $totalBooking = [];
        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $getsession = [
                    "SessionID" => $getsessions['SessionID']['Outbound'],
                    "ReferenceNo" => $getsessions['ReferenceNo']['Outbound'],
                    "Provider" => $getsessions['Provider']['Outbound'],
                    "Key" => $getsessions['Key']['Outbound'],
                ];
            } elseif ($i == 2) {
                $getsession = [
                    "SessionID" => $getsessions['SessionID']['Inbound'],
                    "ReferenceNo" => $getsessions['ReferenceNo']['Inbound'],
                    "Provider" => $getsessions['Provider']['Inbound'],
                    "Key" => $getsessions['Key']['Inbound'],
                ];
            }

            $AddPassengerDetailsBody = [
                "SessionID" => $getsession['SessionID'],
                "Key" => $getsession['Key'],
                "ReferenceNo" => $getsession['ReferenceNo'],
                "CustomerInfo" => [
                    "Email" => $bookingData['email'],
                    "Mobile" => $bookingData['phonenumber'],
                    "Address" => "5b 13 Street No 1 Subhash Nagar Metro Gate No 1",
                    "City" => "Delhi",
                    "State" => "Delhi",
                    "CountryCode" => "IN",
                    "CountryName" => "India",
                    "ZipCode" => "110018",
                    "PassengerDetails" => json_decode($bookingData['travllername'], true),
                    "PassengerTicketDetails" => [],
                    "Payment" => (object) [],
                ],
                "SSRInfo" => [],
                "TotalAmount" => "0",
                "SSRAmount" => 0,
                "Discount" => 0,
                "GrandTotalFare" => "0",
                "IsGSTProvided" => false,
            ];
    
            $AddPassengerDetails = AuthenticateController::callApiWithHeadersGal("AddPassengerDetails", $AddPassengerDetailsBody);
    
            if ($AddPassengerDetails['Status'] == "Success") {
    
                $BookingBody = [
                    "SessionID" => $getsession['SessionID'],
                    "Key" => $AddPassengerDetails['Key'],
                    "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                    "Provider" => $getsession['Provider'],
                ];
    
                $Booking = AuthenticateController::callApiWithHeadersGal("Booking", $BookingBody);
                if ($Booking['Status'] == "Hold") {
    
                    $TicketBody = [
                        "SessionID" => $getsession['SessionID'],
                        "Key" => $AddPassengerDetails['Key'],
                        "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                        "Provider" => $getsession['Provider'],
                    ];
    
                    $Ticket = AuthenticateController::callApiWithHeadersGal("Ticket", $TicketBody);
    
                    if ($Ticket['Status'] == "Success") {
    
                        $getBookingBody = [
                            "SessionID" => $getsession['SessionID'],
                            "Key" => $AddPassengerDetails['Key'],
                            "ReferenceNo" => $AddPassengerDetails['ReferenceNo'],
                            "PnrNo" => "",
                            "Provider" => $getsession['Provider'],
                            "FirstName" => "",
                            "LastName" => "",
                            "From" => "",
                            "To" => "",
                        ];
    
                        $getBooking = AuthenticateController::callApiWithHeadersGal("GetBookingDetails", $getBookingBody);
    
                        if ($getBooking['Status'] == "Success") {
    
                            $saveBooking = new Booking;
                            $logs_id = GalileoFlightLog::where('session_id', '=', $getsession['SessionID'])->first('id');
                            $userId = User::where('phone', $bookingData['phonenumber'])->orWhere('email', $bookingData['email'])->first('id');
    
                            if (empty($userId->id)) {
                                $user = new User;
                                $user->name = "Enter Full Name";
                                $user->email = strtolower($bookingData['email']);
                                $user->phone = $bookingData['phonenumber'];
                                $user->password = Hash::make("Make@123");
                                $user->save();
                                $userId = $user->id;
                            } else {
                                $userId = $userId->id;
                            }
    
                            $saveBooking->booking_from = "GALILEO";
                            $saveBooking->booking_id = $input['uniqueid'];
                            $saveBooking->trip = $getBooking['AirBookingResponse'][0]['Trip'];
                            $saveBooking->trip_type = $getBooking['AirBookingResponse'][0]['TripType'];
                            $saveBooking->trip_stop = "0-Stop";
                            $saveBooking->gds_pnr = $getBooking['AirBookingResponse'][0]['PNR'];
                            $saveBooking->airline_pnr = $getBooking['AirBookingResponse'][0]['FlightDetails'][0]['AirLinePNR'];
                            $saveBooking->email = $getBooking['AirBookingResponse'][0]['CustomerInfo']['Email'];
                            $saveBooking->mobile = $getBooking['AirBookingResponse'][0]['CustomerInfo']['Mobile'];
                            $saveBooking->itinerary = json_encode($getBooking['AirBookingResponse'][0]['FlightDetails'], true);
                            $saveBooking->baggage = json_encode($getBooking['AirBookingResponse'][0]['PaxBaggageDetails'], true);
                            $saveBooking->passenger = json_encode($getBooking['AirBookingResponse'][0]['CustomerInfo']['PassengerTicketDetails'], true);
                            $saveBooking->fare = json_encode($getBooking['AirBookingResponse'][0]['FareDetails'], true);
                            $saveBooking->status = $getBooking['AirBookingResponse'][0]['BookingStatus'];
                            $saveBooking->logs_id = $logs_id->id;
                            $saveBooking->user_id = $userId;
                            $saveBooking->save();

                            if ($i == 2) {  
                                return view('flight-pages.booking-confirm.oneway-gal-flight-booking-confirm')->with('bookings', $saveBooking);
                            }
                        }
                    
                    } else {
    
                        return redirect()->route('error')->with('msg', "Your Booking Has Been Faild!");
                    }
    
                } else {
    
                    return redirect()->route('error')->with('msg', "Your Ticket Booking Faild. Kindly contact on this number 7669988012 for further concern.");
                }
    
            } else {
    
                return redirect()->route('error')->with('msg', "Passenger Data Invalid. Kindly contact on this number 7669988012 for further concern.");
            }

        }
    }

}
