@extends('layouts.master')
@section('title', 'Wagnistrip')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>
@section('body')
@php
    use App\Models\Agent\Agent;
    $Agent = Session()->get("Agent");
    if($Agent != null){
        $mail = $Agent->email;
        $Agent = Agent::where('email', '=', $mail)->first();
        $Charge = 50;
    }else{
        $Charge = 177;
    }
@endphp
    <!-- DESKTOP VIEW START -->
  
<section class="progressteps"> 
    <div class="container">
        <div class="row d-block-in-mb">
            <div class="col-4 col-md-4 col-sm-6 commonTitle_1 w-100-mb">Review your booking</div>
            <div class="col-8 col-md-8 col-sm-6 w-100-mb">
                <div class="progress">
                    <div class="progress-bar commonDesc py-2 bg-success border-right w-25">Flight selected</div>
                    <div class="progress-bar commonDesc py-2 bg-success border-right w-25">Review</div>
                    <div class="progress-bar commonDesc py-2 bg-success w-25 border-right">Traveller Details</div>
                    <div class="progress-bar commonDesc py-2 bg-light w-25 text-dark">Make Payment</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="gl-review-section">
    <div class="container">
        <div class="row flex-row-reverse">

            {{-- Fare Rules Section Start --}}
            <div class="col-sm-4">
                <h5 class="commonTitle fontWeight_600 mb-4">Fare Summary</h5>
                @if ($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] == 0 && $travellers['noOfInfants'] == 0)
                    {{-- Data at Start --}}
                    <div class="faredetailbox bg-white">
                        <div class="innerbox p-2">
                            <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Base Fare</div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ ($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['PaxType']??''). '(' . ($travellers['noOfAdults']??'') . 'X' }} 
                                    <i class="fa fa-inr"></i>
                                    {{ ($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare']??'') . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    @if(isset($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]))
                                    {{ ((int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] ?? '') ?? ''}}</span>
                                    @else
                                    {{ ((int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'] ?? '') ?? ''}}</span>
                                    @endif
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="innerbox p-2">
                            <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Fee & Surcharges</div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Total Fee & Surcharges : </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    @if(isset($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]))
                                    {{ (int) $travellers['noOfAdults'] * (int) ($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['TotalTax']+$Charge)??'' }}</span>
                                    @else
                                    {{ (int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo']+$Charge ??'' }}</span>
                                    @endif
                            </div>
                            
                        </div>
                        <div class="border-bottom"></div>
                        <div class="innerbox p-2">
                            <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Other Services</div>
                            <div class="collapse show w-100">
                                <div class="form-checkk">
                                    <input class="" type="checkbox" value="" id="flexCheckChecked" checked>
                                    <label class="" for="flexCheckChecked">Charity </label>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="ChaAm" class="commonDesc fontWeight_600">10</span></span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div id="price" class="collapse show d-flex justify-content-between w-100">
                                <span class="commonpera fontWeight_500">Discount </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>  <span id="DisAm" class="commonDesc fontWeight_600">0</span></span>
                            </div>
                            <div class="border-bottom"></div>
                            <div id="price" class="collapse show d-flex justify-content-between w-100">
                                <span class="commonpera fontWeight_500">Convenience fee </span>
                                <span class="commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                            </div>
                        </div>

                        <div class="border-bottom"></div>

                        <div class="innerbox p-2">
                            <div class="owstitle pb-10" data-toggle="collapse" data-target="#price1">
                                <span class="commonDesc fontWeight_600"> Total Amount</span>
                                <span class="commonDesc fontWeight_600 float-right"> <i class="fa fa-inr"></i>
                                    @if(!isset($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]))
                                        <script>window.location = "/Flight-not-available";</script>
                                        <?php exit; ?>
                                    @endif
                                    @php 
                                        if(!isset($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0])){
                                            echo `<script>window.location = "/Flight-not-available";</script>`;
                                            exit;
                                        }
                                        $total_fare = (($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['Fare'])+((int) $travellers['noOfAdults']*$Charge));
                                    @endphp
                                    <span id="TotalFare" class="commonDesc fontWeight_600">
                                    {{ $total_fare+10 }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                @elseif($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] != 0 &&
                    $travellers['noOfInfants'] == 0)
                    {{-- Data start --}}
                    <div class="faredetailbox bg-white">

                        <div class="innerbox p-2">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Base Fare</div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['PaxType'] . '(' . $travellers['noOfAdults'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] }}</span>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['PaxType'] . '(' . $travellers['noOfChilds'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfChilds'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] }}</span>
                            </div>
                        </div>

                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Fee & Surcharges

                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Total Fee & Surcharges : </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>

                                @php 
                                    $total_fare = array_sum([($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['FuelSurcharge'])+((int) $travellers['noOfAdults'] * $Charge)+((int) $travellers['noOfChilds'] * $Charge), $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['OtherTax']]);
                                @endphp
                                <span id="TotalFare" class="commonDesc fontWeight_600">
                                    {{ $total_fare+10 }}
                                </span>
                                </span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Other Services</div>
                            <div  class="collapse show">
                            <div class="form-check ">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                <label class="form-check-label" for="flexCheckChecked"> Charity </label>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="ChaAm">10</span></span>
                            </div>
                            </div>
                                <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Discount </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="DisAm">0</span></span>
                            </div> 
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Convenience fee </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="innerbox p-2">
                            <div class="amountdiv" data-toggle="collapse" data-target="#price1">
                                <span class="commonDesc fontWeight_600"> Total Amount</span>
                                <span class="commonDesc fontWeight_600 float-right"> <i class="fa fa-inr"></i>
                                    {{ ($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['Fare']) +((int) $travellers['noOfAdults'] * $Charge)+((int) $travellers['noOfChilds'] * $Charge) }} </span>
                                </div>
                        </div>
                    </div>

                @elseif($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] == 0 &&
                    $travellers['noOfInfants'] != 0)

                    {{-- Data start --}}
                    <div class="faredetailbox bg-white">

                        <div class="innerbox p-2">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Base Fare</div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['PaxType'] . '(' . $travellers['noOfAdults'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] }}</span>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['PaxType'] . '(' . $travellers['noOfInfants'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfInfants'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] }}</span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Fee & Surcharges

                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Total Fee & Surcharges : </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                @php 
                                    $total_fare = array_sum([($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['FuelSurcharge'])+((int) $travellers['noOfAdults'] * $Charge), $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['OtherTax']]);
                                @endphp
                                <span id="TotalFare" class="commonDesc fontWeight_600">
                                    {{ $total_fare+10 }}
                                </span>
                                </span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Other Services</div>
                            {{--<div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Charity </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                            </div> --}}
                            <div  class="collapse show">
                                <div class="form-checkk">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                    <label class="form-check-label" for="flexCheckChecked">Charity </label>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="ChaAm" class="commonDesc fontWeight_600">10</span></span>
                                </div>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Discount </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="DisAm" class="commonDesc fontWeight_600">0</span></span>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Convenience fee </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="owstitle pb-10" data-toggle="collapse" data-target="#price1">
                                <span class="commonDesc fontWeight_600">Total Amount</span>
                                <span class="commonDesc fontWeight_600 float-right"> <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['Fare'] +((int) $travellers['noOfAdults'] * $Charge)}}</span>
                            </div>
                        </div>
                    </div>

                @elseif($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] != 0 && $travellers['noOfInfants'] != 0)

                    {{-- Data start --}}
                    <div class="boxunder p-2">

                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Base Fare

                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['PaxType'] . '(' . $travellers['noOfAdults'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfAdults'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][0]['BaseFare'] }}</span>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['PaxType'] . '(' . $travellers['noOfChilds'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfChilds'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][1]['BaseFare'] }}</span>
                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][2]['PaxType'] . '(' . $travellers['noOfInfants'] . 'X' }}
                                    <i class="fa fa-inr"></i>
                                    {{ $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][2]['BaseFare'] . ')' }}
                                </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ (int) $travellers['noOfInfants'] * (int) $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['FareBreakDowns']['FareBreakDown'][2]['BaseFare'] }}</span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="ranjepp">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Fee & Surcharges

                            </div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Total Fee & Surcharges : </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                    {{ array_sum([($response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['FuelSurcharge'])+((int) $travellers['noOfChilds'] * $Charge)+((int) $travellers['noOfAdults'] * $Charge), $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['OtherTax']]) }}</span>
                            </div>
                        </div>
                        <div class="border-bottom"></div>
                        <div class="">
                            <div class="commonDesc fontWeight_600 pb-10" data-toggle="collapse" data-target="#price">Other Services</div>
                            <div class="borderbotum"></div>
                            <div  class="collapse show">
                                <div class="form-check ">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                    <label class="form-check-label" for="flexCheckChecked"> Charity </label>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="ChaAm" class="commonDesc fontWeight_600">10</span></span>
                                </div>
                            </div>

                            <div class="borderbotum"></div>
                                <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Discount </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> <span id="DisAm" class="commonDesc fontWeight_600">0</span></span>
                            </div>

                            <div class="borderbotum"></div>
                            <div id="price" class="collapse show">
                                <span class="commonpera fontWeight_500">Convenience fee </span>
                                <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                            </div>
                        </div>

                        <div class="border-bottom"></div>
                        <div class="">
                            <div class="pb-10" data-toggle="collapse" data-target="#price1">
                                <span class="commonDesc fontWeight_600"> Total Amount</span>
                                <span class="commonDesc fontWeight_600 float-right"> <i class="fa fa-inr"></i>
                                    
                                @php 
                                    $total_fare = $response['AirPricingResponse'][0]['PricingInfos']['PricingInfo'][0]['Total']['Fare'] +((int) $travellers['noOfChilds'] * $Charge)+((int) $travellers['noOfAdults'] * $Charge);
                                @endphp
                                <span id="TotalFare" id="commonDesc fontWeight_600">
                                    {{ $total_fare+10 }}
                                </span>
                                </span>
                            </div>
                        </div>
                    </div>

                @endif

                <div class="boxunder" id="boxunder">
                    <div class="card-header commonpera fontWeight_600 bg-info">HOT DEALS</div>
                    <div class="bg-white p-2">
                        <div class="form-checkk cursorPointer d-flex">
                            <input class="form-check-inputt radio-toolbar" type="radio" name="DisAmou" id="flexRadioDefault1" value="Yes">
                            <label class="form-check-label" for="flexRadioDefault1"><b id="DisText" class="commonpera fontWeight_600 cursorPointer">Use this coupon and get Rs 50 instant discount on your flight booking.</b> </label>
                        </div>
                        <div id="remove-btn" class="form-checkk cursorPointer">
                            <input class="form-check-inputt radio-toolbar" type="radio" name="DisAmou" id="flexRadioDefault2" value="No" checked>
                            <label class="form-check-label commonpera fontWeight_600 cursorPointer" for="flexRadioDefault2">Remove</label>
                        </div>
                        {{-- <div class="owstitle"> <i class="fa fa-tag"></i> FLYFLASH</div>
                        <p class="onwfnt-11">Use this code to get special discount of INR 350 for you</p>
                        <div class="borderbotum"></div>
                        <div class="owstitle"> <i class="fa fa-tag"></i> FLYFLASH</div>
                        <p class="onwfnt-11">Use this code to get special discount of INR 350 for you</p>
                        <div class="borderbotum"></div>
                        <div class="owstitle"> <i class="fa fa-tag"></i> FLYFLASH</div>
                        <p class="onwfnt-11">Use this code to get special discount of INR 350 for you</p>
                        <div class="borderbotum"></div>
                        <div class="owstitle"> <i class="fa fa-tag"></i> FLYFLASH</div>
                        <p class="onwfnt-11">Use this code to get special discount of INR 350 for you</p>
                        <div class="borderbotum"></div>--}}
                    </div>
                    @if($Agent != null)
                        <script>
                        
                        setTimeout(() => {
                            $('#boxunder').remove();
                        }, 800);
                        
                        </script>
                    @endif
                </div>
                
            </div>

            {{-- Fare Rules Section End --}}
            <div class="col-sm-8">
                @php
                    use App\Http\Controllers\Airline\AirportiatacodesController;
                @endphp
                {{-- <div class="scrollfix"> --}}
                <h4 class="commonTitle fontWeight_600 mb-4">Itinerary</h4>
                @if (isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]) && isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]) && !isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][2]))
                    @php
                        $originCountry = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode'];
                        $destinationCountry = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['AirportCode'];
                        $jurneyDate = getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']);
                        $SessionID = $SessionID;
                        $ReferenceNo = $response['ReferenceNo'];
                        $Key = $response['Key'];
                        $Provider = $response['AirPricingResponse'][0]['Provider'];
                        $GalFlight = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'];
                    @endphp
                    <div class="bg-white p-2 mb-4">
                        <div class="row pb-2 hide-elements-in-mb">

                            <div class="col-7 col-md-7 col-sm-6 d-flex align-items-center">
                                <span class="bg-dark px-3 rounded-right text-light d-block mr-2">DEPART</span>
                                <div class="dpf">
                                    <span
                                        class="commonDesc fontWeight_700 d-block">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode'] . '-' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['AirportCode'] }} | 
                                        <small class="commonpera fontWeight_700">{{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']) }} </small>
                                    </span>
                                    <span class="commonpeata fontWeight_700 ">1-Stop</span> |
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Duration'] }}</span>
                                </div>
                            </div>

                            <div class="col-5 col-md-5 col-sm-6">
                                <div class="float-right clear-float-in-mb">
                                    <span class="commonDesc fontWeight_700"> Cancellation Fees Apply </span>
                                    <span class="commonDesc fontWeight_700 px-2">|</span>
                                    <span class="commonDesc fontWeight_700 text-center"> Fare Rules </span>
                                </div>
                            </div>

                        </div>

                        <div class="border-bottom hide-elements-in-mb"></div>
                        @php
                        $img=$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code'];
                        @endphp
                        <input type="hidden" id="imgtype" value='$img' name="image">

                        <div class="row pt-4 gl-flightreview-card">
                            <div class="col-2 col-md-2 col-sm-2 divflightlogo">
                                <img src="{{ asset('assets/images/flight/' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code']) }}.png" alt="flight" class="airlinelogo" loading="lzy">
                                <div class="f-name-and-no">
                                    <div class="commonpeara fontWeight_600">
                                        {{  $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Name'] }}
                                    </div>
                                    <div class="commonpeara fontWeight_600">
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code'] . '-' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Identification'] }}
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-3 col-md-3 col-sm-3 divflighfulldetail">
                                <div class="commonpera fontWeight_600">
                                    {{getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime'])}}
                                </div>

                                <span
                                    class="font-weight-bold">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['CityName'] }}</span>
                                <div class="fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']) }}
                                </div>
                                <div class="graytext commonsmalltext">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-sm-2 d-flex justify-center flex-column align-self-center text-center">
                                <div class="fontWeight_500">
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Duration'] }}
                                </div>
                                <div class="border-bottom"></div>
                            </div>
                            <div class="col-3 col-md-3 col-sm-3">
                                <div class="fontWeight_600">
                                    {{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime']) }}
                                </div>
                                <span
                                    class="font-weight-bold">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['CityName'] }}</span>
                                <div class="fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime']) }}
                                </div>

                                <div class="graytext commonsmalltext divflighfulldetail">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-sm-2 d-flex justify-center flex-column align-self-center text-center faretypediv">
                                <span class="fontWeight_600 mb-2">Fare Type</span>
                                <button type="button" class="btn-sm btn btn-outline-success btnfaretype m-auto">SAVER</button>
                            </div>
                        </div>
                        
                        
                        <div class="col-sm-12 col_sm-121">
                            <div class="borderbotum-2"></div>
                            <div class="borderraduesround layorDiv">
                                <span class="layoretext">
                                    {{getTimeDff_formated( $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime'] , $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['DateTime'])}} {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['CityName'] }}
                                </span>                                
                            </div>
                        </div>
                        
                        
                        <div class="row pb-2 gl-flightreview-card">
                            <div class="col-2 col-md-2 col-sm-2 divflightlogo">
                                <img src="{{ asset('assets/images/flight/' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['AirLine']['Code']) }}.png" alt="flight" class="airlinelogo" loading="lazy">
                                <div class="f-name-and-no">
                                    <div class="commonpeara fontWeight_600">
                                        {{  $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Name'] }}
                                    </div>
                                    <div class="commonpeara fontWeight_600">
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['AirLine']['Code'] . '-' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['AirLine']['Identification'] }}
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-3 col-md-3 col-sm-3 divflighfulldetail">
                                <div class="commonpera fontWeight_600">
                                    {{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['DateTime']) }}
                                </div>
                                <span
                                    class="onwfnt-22 font-weight-bold">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['CityName'] }}</span>
                                <div class="commonpeara fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['DateTime']) }}
                                </div>

                                <div class="graytext commonsmalltext">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Origin']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-sm-2 d-flex justify-center flex-column align-self-center text-center">
                                <div class="commonpeara fontWeight_600">
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Duration'] }}
                                </div>
                                <div class="border-bottom"></div>
                            </div>
                            <div class="col-3 col-md-3 col-sm-3 divflighfulldetail">
                                <div class="commonpeara fontWeight_600">
                                    {{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['DateTime']) }}
                                </div>
                                <span
                                    class="fontWeight_600">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['CityName'] }}</span>
                                    
                                <div class="commonpeara fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['DateTime']) }}
                                </div>

                                <div class="graytext commonsmalltext">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]['Destination']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-sm-2 d-flex justify-center flex-column align-self-center text-center faretypediv">
                                <span class="fontWeight_600 mb-2">Fare Type</span>
                                <button type="button" class="btn-sm btn btn-outline-success btnfaretype m-auto">SAVER</button>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <table class="table">
                                <thead>
                                    <tr class="onwfnt-11">
                                        <th class="border commonpeara fontWeight_600">BAGGAGE</th>
                                        <th class="border commonpeara fontWeight_600">CHECK IN</th>
                                        <th class="border commonpeara fontWeight_600">CABIN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="onwfnt-11">
                                        <td class="border commonpeara fontWeight_600">ADULT</td>
                                        <td class="border commonpeara fontWeight_600">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckIn'] != 0 ? $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckIn'] . 'KG' : $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckInPiece'] . 'PC' }}</td>
                                        <td class="border commonpeara fontWeight_600">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['Cabin'] != 0 ? $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['Cabin'] . 'KG' : $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CabinPiece'] . 'PC' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @elseif (isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]) &&
                    !isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][1]) &&
                    !isset($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][2]))

                    @php
                        $originCountry = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode'];
                        $destinationCountry = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['AirportCode'];
                        $jurneyDate = getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']);
                        $SessionID = $SessionID;
                        $ReferenceNo = $response['ReferenceNo'];
                        $Key = $response['Key'];
                        $Provider = $response['AirPricingResponse'][0]['Provider'];
                        $GalFlight = $response['AirPricingResponse'][0]['Itineraries']['Itinerary'];
                    @endphp
                    <div class="bg-white p-2 mb-4">
                        <div class="row pb-2 mn-2 hide-elements-in-mb">

                            <div class="col-7 col-md-7 col-sm-6 d-flex align-items-center">
                                <span class="bg-dark px-3 rounded-right text-light d-block mr-2">DEPART</span>
                                <span class="dpf">
                                    <span
                                        class="commonDesc fontWeight_700 d-block">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode'] . '-' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['AirportCode'] }}
                                        | <small
                                            class="commonpera fontWeight_700">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime'] }}</small>
                                    </span>
                                    <span class="commonpeata fontWeight_700"> Non-Stop |
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Duration'] }}</span>
                                </span>
                            </div>
                            <div class="col-5 col-md-5 col-sm-6">
                                <div class="float-right ranjepp">
                                    <span class="commonDesc fontWeight_700"> Cancellation Fees Apply </span>
                                    <span class="commonDesc fontWeight_700">|</span>
                                    <span class="commonDesc fontWeight_700"> Fare Rules </span>
                                </div>
                            </div>

                        </div>

                        <div class="border-bottom hide-elements-in-mb"></div>

                        <div class="row py-3 gl-flightreview-card">
                            <div class="col-2 col-md-2 col-sm-2 divflightlogo">
                                <img src="{{ asset('assets/images/flight/' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code']) }}.png" alt="flight" class="airlinelogo" loading="lazy">
                                <div class="f-name-and-no">
                                    <div class="commonpeara fontWeight_600">
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Name'] }}
                                    </div>
                                    <div class="commonpeara fontWeight_600">
                                        {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code'] . '-' . $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Identification'] }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-3 col-md-3 col-sm-3 divflighfulldetail">
                                <div class="commonpera fontWeight_600">
                                    {{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']) }}
                                </div>
                                <span
                                    class="onwfnt-22 font-weight-bold">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['CityName'] }}</span>
                                <div class="commonpeara fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']) }}
                                </div>

                                <div class="graytext commonsmalltext">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            
                            <div class="col-2 col-md-2 col-sm-2 text-center  d-flex justify-center flex-column align-self-center">
                                <div class="fontWeight_600">
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Duration'] }}
                                </div>                                
                                <div class="border-bottom"></div>
                            </div>

                            <div class="col-3 col-md-3 col-sm-3 divflighfulldetail">
                                <div class="commonpera fontWeight_600">
                                    {{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime']) }}
                                </div>
                                <span
                                    class="commonpera font-weight-bold">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['CityName'] }}</span>
                                <div class="commonpera fontWeight_600">
                                    {{ getDateFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime']) }}
                                </div>

                                <div class="graytext commonsmalltext">
                                    {{ AirportiatacodesController::getAirport($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['AirportCode']) }}
                                </div>
                                <div class="graytext commonsmalltext">Terminal
                                    {{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['Terminal'] ?? '' }}
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-sm-2 d-flex justify-center flex-column align-self-center text-center faretypediv">
                                <span class="fontWeight_600 mb-2">Fare Type</span>
                                <button type="button" class="btn-sm btn btn-outline-success btnfaretype m-auto">SAVER</button>
                            </div>
                        </div>


                        <div class="d-flex tablediv">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="border commonpeara fontWeight_600">BAGGAGE</th>
                                        <th class="border commonpeara fontWeight_600">CHECK IN</th>
                                        <th class="border commonpeara fontWeight_600">CABIN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border commonpeara fontWeight_600">ADULT</td>
                                        <td class="border commonpeara fontWeight_600">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckIn'] != 0 ? ($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckIn']) . 'KG' : $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CheckInPiece'] . 'PC' }}</td>
                                        <td class="border commonpeara fontWeight_600">{{ $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['Cabin'] != 0 ? $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['Cabin'] . 'KG' : $response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Baggage']['Allowance']['CabinPiece'] . 'PC' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif

                <div class="row m-0">
                    <div id="information" class="collapse p-3 bg-info-gradient">
                        <div class="boxunder p-10 bgpolicy">
                            <h4 class="commonDesc fontWeight_600 py-2">Important Information</h4>
                            <h6 class="commonDesc fontWeight_600 py-2"> <i class="fa fa-info-circle text-red"></i> Mandatory check-list for passengers </h6>
                            <ul class="onwfnt-11">
                                <li class="fontWeight_500">Vaccination requirements : None.</li>
                                <li class="fontWeight_500">COVID test requirements : Non-vaccinated passengers entering the state from Maharashtra and Kerala must carry a negative RT-PCR test report with a sample taken within 72 hours before arrival. RT-PCR Test timeline starts from the swab collection time. Negative RT-PCR test report is not required for passengers travelling from other states.</li>
                                <li class="fontWeight_500">Passengers travelling to the state might not be allowed to board their flights if they are not carrying a valid test report.</li>
                                <li class="fontWeight_500">Pre-registration or e-Pass requirements : Download and update Aarogya Setu app</li>
                                <li class="fontWeight_500">Quarantine Guidelines : None</li>
                                <li class="fontWeight_500">Destination Restrictions : A lockdown is in effect at the moment, however, flight operations remain unaffected during this time. Please check the latest state guidelines before travelling.</li>
                                <li class="fontWeight_500">Remember to web check-in before arriving at the airport; carry a printed or soft copy of the boarding pass.</li>
                                <li class="fontWeight_500">Please reach at least 2 hours prior to flight departure.</li>
                                <li class="fontWeight_500">The latest DGCA guidelines state that it is compulsory to wear a mask that covers the nose and mouth properly while at the airport and on the flight. Any lapse might result in de-boarding. Know More </li>
                                <li class="fontWeight_500">Remember to download the baggage tag(s) and affix it on your bag(s). </li>
                            </ul>
                            <h6 class="commonDesc fontWeight_600 py-2"> <i class="fa fa-info-circle text-red"></i> State Guidelines </h6>
                            <ul class="onwfnt-11">
                                <li class="fontWeight_500">Check the detailed list of travel guidelines issued by Indian States and UTs.Know More</li>
                            </ul>
                            <h6 class="commonDesc fontWeight_600 py-2"> <i class="fa fa-info-circle text-red"></i> Baggage information </h6>
                            <ul class="onwfnt-11">
                                <li class="fontWeight_500">Carry no more than 1 check-in baggage and 1 hand baggage per passenger. Additional pieces of Baggage will be subject to additional charges per piece in addition to the excess baggage charges.</li>
                            </ul>
                            <h6 class="commonDesc fontWeight_600 py-2"> <i class="fa fa-info-circle text-red"></i> A Note on Guidelines </h6>
                            <ul class="onwfnt-11">
                                <li class="fontWeight_500">Disclaimer: The information provided above is only for ready reference and convenience of customers, and may not be exhaustive. We strongly recommend that you check the full text of the guidelines issued by the State Governments before travelling. Wagnistrip accepts no liability in this regard.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="booking-btn-section" class="collapse in w-100 mt-2"> 
                    <button id="booking-btn" type="button" class="btn blueBtn w-100 commonTitle_1" data-toggle="collapse" data-toggle="collapse in"> CONTINUE</button>
                </div>

                {{-- Travller Form Data Start --}}
                <div id="traveller-section" class="collapse pb-20">
                    <form id="main-form" action="{{ route('galelio-traveller-details') }}" method="post" data-parsley-validate>
                        @csrf
                        <input type="hidden" name="GalFlight" value="{{ json_encode($GalFlight??'', true) }}">
                        <input type="hidden" name="travellers" value="{{ json_encode($travellers, true) }}">
                        <input type="hidden" name="SessionID" value="{{ $SessionID }}">
                        <input type="hidden" name="ReferenceNo" value="{{ $ReferenceNo }}">
                        <input type="hidden" name="Provider" value="{{ $Provider }}">
                        <input type="hidden" name="Key" value="{{ $Key }}">
                        
                        <input type="hidden" name="code" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code']}}">
                        <input type="hidden" name="time1" value="{{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['DateTime']) }}">
                        <input type="hidden" name="time2" value="{{ getTimeFormat($response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['DateTime']) }}">
                        <input type="hidden" name="city1" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Origin']['CityName']}}">
                        <input type="hidden" name="city2" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Destination']['CityName']}}">
                        <input type="hidden" name="delay" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['Duration']}}">
                        <input type="hidden" name="stop" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['StopCount']}}">
                        <input type="hidden" name="otherInformations" value="{{$response['AirPricingResponse'][0]['Itineraries']['Itinerary'][0]['AirLine']['Code']}}">
                        <input type="hidden" id="Chari"name="Chari">
                        <input type="hidden" id="textDis"name="textDis" value="no">
                        <input type="hidden" name="trip" value="Oneway">
                        @php
                            session(['total_fare'=>$total_fare]);
                        @endphp
                        <input type="hidden" name="fare" value="{{ $total_fare }}">
                        <br>
                        {{-- <span class="searchtitle">Enter code*</span>
                        <input type="text" name="Code" class="form-control" placeholder="Enter code" value="{{ $total_fare }}" />
                        <br> --}}
                        <x-flight.travellerform :travellers="$travellers" :originCountry="$originCountry"
                            :destinationCountry="$destinationCountry" :jurneyDate="$jurneyDate" />
                    </form>
                    
                </div>
                {{-- Travller Form Data End --}}
            </div>

        </div>
    </div>
</section>


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/userstyle.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
@stop
@section('script')
<script>
    $(document).ready(function () {
      $('#travller-btn').on('click', function () {
        $('#main-form').submit();
      });
  
    let checkbox = document.getElementById("flexCheckChecked");
    let DisAmBox = document.getElementById("flexRadioDefault1");
    let DisText = document.getElementById("DisText");
    let text = document.getElementById("Chari");
    let textDis = document.getElementById("textDis");
    let TotalFare = document.getElementById("TotalFare");
    let ChaAm = document.getElementById("ChaAm");
    let DisAm = document.getElementById("DisAm");
    let removrBtn = document.getElementById("remove-btn");
        checkbox.addEventListener( "change", () => {
              // console.log(TotalFare , TotalFare.innerText);
           if ( checkbox.checked ) {
      text.value = "yes";
    TotalFare.innerText = parseInt(TotalFare.innerText) +10;
    ChaAm.innerText = parseInt(ChaAm.innerText) +10;
           } else {
      text.value = "no";
    TotalFare.innerText = parseInt(TotalFare.innerText) - 10;
    ChaAm.innerText = parseInt(ChaAm.innerText) - 10;
           }
        });
  
    removrBtn.style.display= "none";
    $('input[name=DisAmou]').on('change',function () {
      let Disvalue = $(this).filter(':checked').val();
    if ( Disvalue == 'Yes' ) {
      textDis.value = "yes";
    DisAm.innerText = parseInt(DisAm.innerText) +50;
    TotalFare.innerText = parseInt(TotalFare.innerText) - 50;
    DisText.innerText = "Congratulations! Promo Discount of Rs. 50 applied successfully.";
    DisText.style.color= "green";
    removrBtn.style.display= "block";
    DisText.style.display= "block";
              // console.log($(this).filter(':checked').val() , Disvalue);
           } else {
      textDis.value = "no";
    DisAm.innerText = parseInt(DisAm.innerText) - 50;
    TotalFare.innerText = parseInt(TotalFare.innerText) + 50;
    DisText.innerText = "Use this coupon and get Rs 50 instant discount on your flight booking.";
    DisText.style.color= "black";
    removrBtn.style.display= "none";
    removrBtn.style.color= "black";
           }
              // console.log($(this).filter(':checked').val() , Disvalue);
      });
  
      //   DisAmBox.addEventListener( "change", () => {
      //     // console.log(DisAmBox , DisAmBox.value);
      //      if ( checkbox.checked ) {
      //         textDis.value = "yes";
      //         DisAm.innerText = parseInt(DisAm.innerText) +50;
      //         TotalFare.innerText = parseInt(TotalFare.innerText) - 50;
      //      } else {
      //         textDis.value = "no";
      //         DisAm.innerText = parseInt(DisAm.innerText) - 50;
      //         TotalFare.innerText = parseInt(TotalFare.innerText) + 50;
      //      }
      //   });--}}

  /////// API Data code By Neelesh ////// 
     
  var divs = document.getElementsByClassName('owstitle1');
    var uniqueValues = [];
  
    for (let i = 0; i < divs.length; i++) {
      let iddata = divs[i].innerHTML;
  
    if (!uniqueValues.includes(iddata)) {
      uniqueValues.push(iddata);
    fetchAirlineCode(iddata, divs);
    }
  }
  
    function fetchAirlineCode(iddata, divs) {
      fetch("/api/airlinecode?search=" + iddata)
        .then(response => response.text())
        .then(data => {
          for (let i = 0; i < divs.length; i++) {
            if (divs[i].innerHTML === iddata) {
              divs[i].innerHTML = data;
            }
          }
        })
        .catch(error => console.error(error));
  }
  
    ///////  End  Code By Neelesh //////--}}
  
  
    $("#booking-btn-section").show();
    $("#traveller-section").hide();
    $("#payment-section").hide();
    $("#information").show();
  
    $("#booking-btn").click(function() {
      $("#booking-btn-section").hide();
    $("#traveller-section").show();
    $("#information").hide();
          });
    $("#travller-btn").click(function() {
      // $(this).hide();
      $("#payment-section").show();
          });
  
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
          });
  
    function togglePopup() {
      $(".content").toggle();
          }
      });
  </script>
    
    <script>
        window.addEventListener( "pageshow", function ( event ) {
            var historyTraversal = event.persisted ||
        ( typeof window.performance != "undefined" &&
        window.performance.navigation.type === 2 );
        if ( historyTraversal ) {
          // Handle page restore.
          window.location.reload();
            }
          });
      </script>
    
@stop
@endsection
