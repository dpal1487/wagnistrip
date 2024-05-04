<?php

namespace App\Http\Controllers\Airline\Amadeus;

use Amadeus\Client;
use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;
use Amadeus\Client\RequestOptions\PnrCreatePnrOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\Pnr\Element\Contact;
use Amadeus\Client\RequestOptions\Pnr\Element\FormOfPayment;
use Amadeus\Client\RequestOptions\Pnr\Element\Ticketing;
use Amadeus\Client\RequestOptions\Pnr\Itinerary;
use Amadeus\Client\RequestOptions\Pnr\Segment;
use Amadeus\Client\RequestOptions\Pnr\Segment\Miscellaneous;
use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use Amadeus\Client\Result;
use App\Http\Controllers\Controller;
use App\Models\Booking\Bookingpnr;
use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Razorpay\Api\Api;
use Session;

class DomesticPnrAddMultiElementsController extends Controller
{

    // {"otherInfoOutbound":{"marketingCompany":"UK","operatingCompany":"UK","arrivalingTime":"0210","arrivalDate":"201021"},"otherInfoInbound":{"marketingCompany_1":"UK","marketingCompany_2":"UK","operatingCompany_1":"UK","operatingCompany_2":"UK","arrivalingTime":"0630","arrivalDate_1":null,"arrivalDate_2":null}}

    public function DomPnrAddMultiElements(Request $request)
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

        $bookingData = Cart::where('uniqueid', $request['uniqueID'])->first();

        $otherInformation = json_decode($bookingData['otherInformation'], true);
        $OutboundMarketingCompany = $otherInformation['otherInfoOutbound']['marketingCompany'] ?? $otherInformation['otherInfoOutbound']['marketingCompany_1'];
        $InboundMarketingCompany = $otherInformation['otherInfoInbound']['marketingCompany'] ?? $otherInformation['otherInfoInbound']['marketingCompany_1'];

        $activeTravellers = json_decode($bookingData['travllername'], true);

        $getsession = json_decode($bookingData['getsession'], true);

        $OutboundGetsession = $getsession['sessionOutbound'];
        $InboundGetsession = $getsession['sessionInbound'];

        $phonenumber = $bookingData['phonenumber'];
        $email = $bookingData['email'];
        $uniqueid = $bookingData['uniqueid'];

        for ($r = 1; $r <= 2; $r++) {

            $HeaderController = new AmadeusHeaderController;
            $params = $HeaderController->State(true);
            $passengers = json_decode($bookingData['travellerquantity'], true);
            $client = new Client($params);

            if ($r == 1) {
                $marketingCompany = $OutboundMarketingCompany;
                $client->setSessionData($OutboundGetsession);
            } elseif ($r == 2) {
                $marketingCompany = $InboundMarketingCompany;
                $client->setSessionData($InboundGetsession);
            }

            $travellers = [];
            $pricing = [];

            if ((int) $passengers['noOfChilds'] === 0 && (int) $passengers['noOfInfants'] === 0) {
                for ($i = 0; $i < $passengers['noOfAdults']; $i++) {

                    $trvlrs = new Traveller([
                        'number' => $i,
                        'firstName' => $activeTravellers['adults']['fistName'][$i],
                        'lastName' => $activeTravellers['adults']['lastName'][$i],
                        'type' => 'ADT',
                    ]);
                    array_push($travellers, $trvlrs);
                }

                $pricing = new TicketCreateTstFromPricingOptions([
                    'pricings' => [
                        new Pricing([
                            'tstNumber' => 1,
                        ]),
                    ],
                ]);
            } elseif ((int) $passengers['noOfChilds'] === 0 && (int) $passengers['noOfInfants'] > 0) {
                for ($i = 0; $i < $passengers['noOfAdults']; $i++) {

                    if ($passengers['noOfInfants'] != 0 && $i < $passengers['noOfInfants']) {
                        if ($activeTravellers['adults']['fistName'][$i] == $activeTravellers['infants']['fistName'][$i] && $activeTravellers['adults']['lastName'][$i] == $activeTravellers['infants']['lastName'][$i]) {
                            $trvlrs = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'travellerType' => null,
                                'infant' => new Traveller([
                                    'firstName' => 'Junior',
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        } elseif ($activeTravellers['adults']['fistName'][$i] == $activeTravellers['infants']['fistName'][$i] && $activeTravellers['adults']['lastName'][$i] != $activeTravellers['infants']['lastName'][$i]) {
                            $trvlrs = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'infant' => new Traveller([
                                    'firstName' => $activeTravellers['infants']['fistName'][$i],
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        } else {
                            $trvlrs = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'infant' => new Traveller([
                                    'firstName' => $activeTravellers['infants']['fistName'][$i],
                                    'lastName' => $activeTravellers['infants']['lastName'][$i],
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        }
                    } else {
                        $trvlrs = new Traveller([
                            'number' => $i,
                            'firstName' => $activeTravellers['adults']['fistName'][$i],
                            'lastName' => $activeTravellers['adults']['lastName'][$i],
                            'type' => 'ADT',
                        ]);

                    }
                    array_push($travellers, $trvlrs);

                }

                $pricing = new TicketCreateTstFromPricingOptions([
                    'pricings' => [
                        new Pricing([
                            'tstNumber' => 1,
                        ]),
                        new Pricing([
                            'tstNumber' => 2,
                        ]),
                    ],
                ]);
            } elseif ((int) $passengers['noOfChilds'] > 0 && (int) $passengers['noOfInfants'] === 0) {

                for ($i = 0; $i < $passengers['noOfAdults']; $i++) {

                    $trvlrs1 = new Traveller([
                        'number' => $i,
                        'firstName' => $activeTravellers['adults']['fistName'][$i],
                        'lastName' => $activeTravellers['adults']['lastName'][$i],
                        'type' => 'ADT',
                    ]);
                    array_push($travellers, $trvlrs1);
                }
                for ($i = 0; $i < $passengers['noOfChilds']; $i++) {

                    $trvlrs2 = new Traveller([
                        'number' => array_sum([$passengers['noOfAdults'], $i]),
                        'firstName' => $activeTravellers['childs']['fistName'][$i],
                        'lastName' => $activeTravellers['childs']['lastName'][$i],
                        'travellerType' => Traveller::TRAV_TYPE_CHILD,
                    ]);

                    array_push($travellers, $trvlrs2);

                }
                $pricing = new TicketCreateTstFromPricingOptions([
                    'pricings' => [
                        new Pricing([
                            'tstNumber' => 1,
                        ]),
                        new Pricing([
                            'tstNumber' => 2,
                        ]),
                    ],
                ]);
            } elseif ((int) $passengers['noOfChilds'] > 0 && (int) $passengers['noOfInfants'] > 0) {
                for ($i = 0; $i < $passengers['noOfAdults']; $i++) {

                    if ($passengers['noOfInfants'] != 0 && $i < $passengers['noOfInfants']) {
                        if ($activeTravellers['adults']['fistName'][$i] == $activeTravellers['infants']['fistName'][$i] && $activeTravellers['adults']['lastName'][$i] == $activeTravellers['infants']['lastName'][$i]) {
                            $trvlrs1 = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'infant' => new Traveller([
                                    'firstName' => 'Junior',
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        } elseif ($activeTravellers['adults']['fistName'][$i] == $activeTravellers['infants']['fistName'][$i] && $activeTravellers['adults']['lastName'][$i] != $activeTravellers['infants']['lastName'][$i]) {
                            $trvlrs1 = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'infant' => new Traveller([
                                    'firstName' => $activeTravellers['infants']['fistName'][$i],
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        } else {
                            $trvlrs1 = new Traveller([
                                'number' => $i,
                                'firstName' => $activeTravellers['adults']['fistName'][$i],
                                'lastName' => $activeTravellers['adults']['lastName'][$i],
                                'infant' => new Traveller([
                                    'firstName' => $activeTravellers['infants']['fistName'][$i],
                                    'lastName' => $activeTravellers['infants']['lastName'][$i],
                                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', $activeTravellers['infants']['infantDOB'][$i], new \DateTimeZone('UTC')),
                                ]),
                            ]);

                        }
                        array_push($travellers, $trvlrs1);
                    } else {
                        $trvlrs2 = new Traveller([
                            'number' => $i,
                            'firstName' => $activeTravellers['adults']['fistName'][$i],
                            'lastName' => $activeTravellers['adults']['lastName'][$i],
                            'type' => 'ADT',
                        ]);
                        array_push($travellers, $trvlrs2);
                    }
                }

                for ($i = 0; $i < $passengers['noOfChilds']; $i++) {

                    $trvlrs3 = new Traveller([
                        'number' => array_sum([$passengers['noOfAdults'], $i]),
                        'firstName' => $activeTravellers['childs']['fistName'][$i],
                        'lastName' => $activeTravellers['childs']['lastName'][$i],
                        'travellerType' => Traveller::TRAV_TYPE_CHILD,
                    ]);

                    array_push($travellers, $trvlrs3);

                }
                $pricing = new TicketCreateTstFromPricingOptions([
                    'pricings' => [
                        new Pricing([
                            'tstNumber' => 1,
                        ]),
                        new Pricing([
                            'tstNumber' => 2,
                        ]),
                        new Pricing([
                            'tstNumber' => 3,
                        ]),

                    ],
                ]);
            }

            $opt = new PnrCreatePnrOptions();
            $opt->actionCode = PnrCreatePnrOptions::ACTION_NO_PROCESSING; //0 Do not yet save the PNR and keep in context.

            $opt->travellers = $travellers;

            $opt->elements[] = new Ticketing([
                'ticketMode' => Ticketing::TICKETMODE_OK,
            ]);
            $opt->itineraries[] = new Itinerary([
                'segments' => [
                    new Miscellaneous([
                        'status ' => Segment::STATUS_CONFIRMED,
                        'company' => '1A',
                        'date' => \DateTime::createFromFormat('Ymd', date('Ymd'), new \DateTimeZone('UTC')),
                        'cityCode' => 'DEL',
                        'freeText' => 'MAKE TRUE TRIP (OPC ) PRIVATE LIMITED.',
                        'quantity' => array_sum([$passengers['noOfAdults'], $passengers['noOfChilds']]),
                    ]),
                ],
            ]);

            $opt->elements[] = new Contact([
                'type' => Contact::TYPE_PHONE_MOBILE,
                'value' => '9875489875',
            ]);
            $opt->elements[] = new Contact([
                'type' => Contact::TYPE_EMAIL,
                'value' => $email,
            ]);
            $opt->elements[] = new FormOfPayment([
                'type' => FormOfPayment::TYPE_CASH,
            ]);

            $createdPnr = $client->pnrCreatePnr($opt);

            if ($createdPnr->status === Result::STATUS_OK) {
                $getsession = $client->getSessionData();
                $client->setSessionData($getsession);

                $pricingResponse = $client->farePricePnrWithBookingClass(
                    new FarePricePnrWithBookingClassOptions([
                        'validatingCarrier' => $marketingCompany,
                    ])
                );

                if ($pricingResponse->status === Result::STATUS_OK) {
                    $getsession = $client->getSessionData();
                    $client->setSessionData($getsession);

                    $createTstResponse = $client->ticketCreateTSTFromPricing(
                        $pricing
                    );

                    if ($createTstResponse->status === Result::STATUS_OK) {
                        $getsession = $client->getSessionData();
                        $client->setSessionData($getsession);
                        $pnrReply = $client->pnrAddMultiElements(
                            new PnrAddMultiElementsOptions([
                                'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT, //ET: END AND RETRIEVE
                            ])
                        );

                        if ($pnrReply->status === Result::STATUS_OK) {
                            $getsession = $client->getSessionData();
                            $client->setSessionData($getsession);
                            sleep(10);
                            $createdPnrForRetriever1 = $pnrReply->response->pnrHeader->reservationInfo->reservation->controlNumber;

                            $pnrRetrieve = $client->pnrRetrieve(new PnrRetrieveOptions(['recordLocator' => $createdPnrForRetriever1]));

                            if ($pnrRetrieve->status === Result::STATUS_OK) {
                                $getsession = $client->getSessionData();
                                $client->setSessionData($getsession);

                                $issueTicketResponse = $client->docIssuanceIssueTicket(
                                    new DocIssuanceIssueTicketOptions([
                                        'options' => [
                                            DocIssuanceIssueTicketOptions::OPTION_ETICKET,
                                        ],
                                    ])
                                );

                                if ($issueTicketResponse->status === Result::STATUS_OK) {
                                    $getsession = $client->getSessionData();
                                    $client->setSessionData($getsession);

                                    $createdPnrForRetriever2 = $pnrRetrieve->response->pnrHeader->reservationInfo->reservation->controlNumber;
                                    $pnrRetrieveAndDisplay = $client->pnrRetrieve(
                                        new PnrRetrieveOptions(['recordLocator' => $createdPnrForRetriever2])
                                    );

                                    if ($pnrRetrieveAndDisplay->status === Result::STATUS_OK) {
                                        $booking = $pnrRetrieveAndDisplay->response;
                                        $getsession = $client->getSessionData();
                                        $client->setSessionData($getsession);

                                        $book = new Bookingpnr;
                                        $book->pnr = $booking->originDestinationDetails->itineraryInfo[1]->itineraryReservationInfo->reservation->controlNumber ?? '';
                                        $seg = [];
                                        foreach ($booking->originDestinationDetails->itineraryInfo as $segkey => $segment) {
                                            if ($segkey > 0) {
                                                $segdata = [
                                                    'operatingcompany' => $segment->travelProduct->companyDetail->identification ?? '',
                                                    'marketingcompany' => $segment->travelProduct->companyDetail->identification ?? '',
                                                    'flightnumber' => $segment->travelProduct->productDetails->identification ?? '',
                                                    'departurelocation' => $segment->travelProduct->boardpointDetail->cityCode ?? '',
                                                    'departureterminal' => $segment->flightDetail->departureInformation->departTerminal ?? '',
                                                    'departuredate' => $segment->travelProduct->product->depDate ?? '',
                                                    'departuretime' => $segment->travelProduct->product->depTime ?? '',
                                                    'arrivallocation' => $segment->travelProduct->offpointDetail->cityCode ?? '',
                                                    'arrivalterminal' => $segment->flightDetail->arrivalStationInfo->terminal ?? '',
                                                    'arrivaldate' => $segment->travelProduct->product->arrDate ?? '',
                                                    'arrivaltime' => $segment->travelProduct->product->arrTime ?? '',
                                                    'journeytime' => $segment->flightDetail->productDetails->duration ?? '',
                                                    'serviceclass' => $segment->travelProduct->productDetails->classOfService ?? '',
                                                    'seat' => '',
                                                    'meal' => '',

                                                ];
                                                array_push($seg, $segdata);
                                            }
                                        }
                                        $book->segment = json_encode($seg, true);
                                        is_array($booking->travellerInfo) ? $travellerInfo = $booking->travellerInfo : $travellerInfo = [$booking->travellerInfo];
                                        $trvl = [];
                                        foreach ($travellerInfo as $travellers) {
                                            $ticketNo = $travellers->elementManagementPassenger->reference->number;
                                            is_array($travellers->passengerData) ? $travellerData = $travellers->passengerData : $travellerData = [$travellers->passengerData];
                                            foreach ($travellerData as $person) {
                                                $data = [
                                                    'ticket' => $ticketNo ?? '',
                                                    'type' => $person->travellerInformation->passenger->type ?? '',
                                                    'first' => $person->travellerInformation->passenger->firstName ?? '',
                                                    'last' => $person->travellerInformation->traveller->surname ?? '',
                                                ];
                                                array_push($trvl, $data);
                                            }
                                        }
                                        $book->travellers = json_encode($trvl, true);
                                        $book->email = $booking->dataElementsMaster->dataElementsIndiv[0]->otherDataFreetext->longFreetext ?? '';
                                        $book->mobile = $booking->dataElementsMaster->dataElementsIndiv[1]->otherDataFreetext->longFreetext ?? '';
                                        $book->carrayon = "7k";
                                        $book->checkin = "15k";
                                        $book->basefare = $booking->tstData->fareData->monetaryInfo[0]->amount ?? '';
                                        $book->totalfare = $booking->tstData->fareData->monetaryInfo[1]->amount ?? '';
                                        $book->airlinetaxes = 0;
                                        $book->ancillarycharges = 0;
                                        $book->donationamount = 0;
                                        $book->conveniencefee = 0;
                                        $book->xmllogs_id = $pnrRetrieveAndDisplay->responseXml ?? "";
                                        $usermobile = User::where('phone', $phonenumber)->pluck('id') ?? '';
                                        $useremail = User::where('email', $email)->pluck('id') ?? '';
                                        if (isset($usermobile[0])) {
                                            $book->user_id = $usermobile[0] ?? '';
                                        } elseif (isset($useremail[0])) {
                                            $book->user_id = $useremail[0] ?? '';

                                        } else {
                                            $user = new User;
                                            $user->name = $activeTravellers['adults']['fistName'][0] . " " . $activeTravellers['adults']['lastName'][0] ?? '';
                                            $user->email = strtolower($email) ?? '';
                                            $user->phone = $phonenumber ?? '';
                                            $user->password = Hash::make("New@1234") ?? '';
                                            $user->save();

                                            $book->user_id = $user->id;
                                        }

                                        $book->save();

                                        $client->securitySignOut();

                                        if ($r == 2) {
                                            return redirect()->route('user-booking')->with('message', 'State saved correctly!'); 
                                        }
                                    }
                                } else {

                                    return redirect()->route('error')->with('message', 'issueTicketResponse  ---- Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

                                }

                            } else {
                                return redirect()->route('error')->with('message', 'pnrRetrieve  ---- Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

                            }
                        } else {
                            return redirect()->route('error')->with('message', 'pnrReply  ---- Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

                        }

                    } else {
                        return redirect()->route('error')->with('message', 'createTstResponse  ---- Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

                    }

                } else {
                    return redirect()->route('error')->with('message', 'pricingResponse -----  Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

                }

            } else {
                return redirect()->route('error')->with('message', 'createdPnr   -----   Your booking could not be completed as we did not receive successful authorisation of the payment from your bank.');

            }

        }
    }

}
