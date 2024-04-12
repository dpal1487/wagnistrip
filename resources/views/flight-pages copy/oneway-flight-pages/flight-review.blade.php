@extends('layouts.master')
@section('title', 'Wagnistrip')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>
@section('body')
    @php
        use App\Models\Agent\Agent;
        $Agent = Session()->get('Agent');
        if ($Agent != null) {
            $mail = $Agent->email;
            $Agent = Agent::where('email', '=', $mail)->first();
            $Charge = 50;
        } else {
            $Charge = 177;
        }
    @endphp


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

    @php
        $infant_dob = date('Y-m-d', strtotime('-728 days'));
        $totalAmount = '';
    @endphp
    <section class="amadus-review-section gl-review-section">
        <div class="container">
            <div class="row flex-row-reverse">
                {{-- Fare Rules Section Start --}}
                <div class="col-sm-4 pt-20">
                    <h5 class="commonTitle fontWeight_600 mb-4">Fare Summary</h5>
                    @php
                        if (is_array($fare->mainGroup->pricingGroupLevelGroup) == true) {
                            $newFare = $fare->mainGroup->pricingGroupLevelGroup;
                        } else {
                            $newFare = [$fare->mainGroup->pricingGroupLevelGroup];
                        }
                    @endphp

                    @if ($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] == 0 && $travellers['noOfInfants'] == 0)
                        {{-- Data at Start --}}
                        <div class="faredetailbox bg-white">

                            <div class="innerbox p-2">
                                <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Base Fare</div>
                                <div id="price" class="collapse show">
                                    <span class="commonpera fontWeight_500">
                                        @php
                                        is_array($newFare[0]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup = $newFare[0]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup = [$newFare[0]->fareInfoGroup->segmentLevelGroup]);
                                        if ($newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                            $monetaryDetails = $newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                            $otherMonetaryDetails = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                                        } else {
                                            $monetaryDetails = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                            $otherMonetaryDetails = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                                        }
                                        @endphp
                                        {{ $segmentLevelGroup[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}<i class="fa fa-inr"></i>
                                        {{ $monetaryDetails . ')' }}
                                    </span>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails }}</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="innerbox p-2">
                                <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Fee & Surcharges</div>
                                <div id="price" class="collapse show">
                                    <span class="commonpera fontWeight_500">Total Fee & Surcharges : </span>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails + $Charge - (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails }}</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="innerbox p-2">
                                <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Other Services</div>
                                <div class="collapse show">
                                    <div class="form-checkk">
                                        <input class="form-check-inputt" type="checkbox" value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked"> Charity</label>
                                        <span class="float-right commonDesc fontWeight_600">
                                            <i class="fa fa-inr"></i> <span id="ChaAm" class="commonDesc fontWeight_600">10</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show d-flex justify-content-between w-100">
                                    <span class="commonDesc fontWeight_600">Discount </span>
                                    <span class="float-right commonDesc fontWeight_600">
                                        <i class="fa fa-inr"></i> 
                                        <span id="DisAm" class="commonDesc fontWeight_600">0</span>
                                    </span>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show d-flex justify-content-between w-100">
                                    <span class="commonDesc fontWeight_600">Convenience fee </span>
                                    <span class="float-right commonDesc fontWeight_600"><i class="fa fa-inr"></i> 0</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="innerbox p-2">
                                <div class="amountdiv" data-toggle="collapse" data-target="#price1">
                                    <span class="commonDesc fontWeight_600"> Total Amount</span>
                                    <span class="commonDesc fontWeight_600 float-right"> <i class="fa fa-inr"></i>
                                        @php $totalAmount =  (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails+$Charge; @endphp
                                        <span id="TotalFare" class="commonDesc fontWeight_600">
                                            {{ $totalAmount + 10 }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif(
                        ($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] != 0 && $travellers['noOfInfants'] == 0) ||
                            ($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] == 0 && $travellers['noOfInfants'] != 0))
                        @php
                            is_array($newFare[0]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup1 = $newFare[0]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup1 = [$newFare[0]->fareInfoGroup->segmentLevelGroup]);
                            is_array($newFare[1]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup2 = $newFare[1]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup2 = [$newFare[1]->fareInfoGroup->segmentLevelGroup]);
                            
                            if ($newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                $monetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                $otherMonetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                            } else {
                                $monetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                $otherMonetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                            }
                            
                            if ($newFare[1]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                $monetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                $otherMonetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                            } else {
                                $monetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                $otherMonetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                            }
                            
                        @endphp
                        {{-- Data start --}}
                        <div class="faredetailbox bg-white">

                            <div class="ranjepp">
                                <div class="commonDesc pb-10" data-toggle="collapse" data-target="#price">Base Fare</div>
                                <div class="p-1">
                                    <span class="commonDesc fontWeight_600">
                                        {{ $segmentLevelGroup1[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}
                                        <i class="fa fa-inr"></i>
                                        {{ $monetaryDetails_1 . ')' }}
                                    </span>
                                    <span class="float-right commonDesc"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_1 }}</span>
                                </div>
                                <div class="p-1">
                                    <span class="commonDesc fontWeight_600">
                                        {{ $segmentLevelGroup2[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}
                                        <i class="fa fa-inr"></i>
                                        {{ $monetaryDetails_2 . ')' }}
                                    </span>
                                    <span class="float-right commonDesc"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_2 }}</span>
                                </div>
                            </div>

                            <div class="border-bottom"></div>
                            <div class="innerbox p-2">
                                <div class="commonDesc fontWeight_600" data-toggle="collapse" data-target="#price">Fee & Surcharges</div>
                                <div class="p-1">
                                    <span class="commonDesc fontWeight_600">Total Fee & Surcharges : </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i>
                                        {{ array_sum([(int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails_1 + $Charge, $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails_2 + $Charge]) - array_sum([(int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_1, $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_2]) }}</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="innerbox p-2">
                                <div class="fontsize-17 pb-10" data-toggle="collapse" data-target="#price">Other Services
                                </div>
                                <div class="collapse show">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Charity
                                        </label>
                                        <span class="float-right fontsize-17"><i class="fa fa-inr"></i> <span
                                                id="ChaAm">10</span></span>
                                    </div>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show">
                                    <span class="font-14">Discount </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i> <span
                                            id="DisAm">0</span></span>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show">
                                    <span class="font-14">Convenience fee </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i> 0</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="ranjepp">
                                <div class="owstitle pb-10" data-toggle="collapse" data-target="#price1">
                                    <span class="commonDesc fontWeight_600"> Total Amount</span>
                                    <span class="fontsize-22 float-right"> <i class="fa fa-inr"></i>
                                        @php $totalAmount = array_sum([(int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails_1+$Charge, (int) $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $otherMonetaryDetails_2+$Charge]); @endphp
                                        <span id="TotalFare" class="commonDesc fontWeight_600">
                                            {{ $totalAmount + 10 }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($travellers['noOfAdults'] != 0 && $travellers['noOfChilds'] != 0 && $travellers['noOfInfants'] != 0)
                        @php
                            is_array($newFare[0]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup1 = $newFare[0]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup1 = [$newFare[0]->fareInfoGroup->segmentLevelGroup]);
                            is_array($newFare[1]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup2 = $newFare[1]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup2 = [$newFare[1]->fareInfoGroup->segmentLevelGroup]);
                            is_array($newFare[2]->fareInfoGroup->segmentLevelGroup) ? ($segmentLevelGroup3 = $newFare[2]->fareInfoGroup->segmentLevelGroup) : ($segmentLevelGroup3 = [$newFare[2]->fareInfoGroup->segmentLevelGroup]);
                        @endphp
                        {{-- Data in --}}
                        <div class="boxunder p-2">

                            <div class="ranjepp">
                                @php
                                    
                                    if ($newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                        $monetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                        $otherMonetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                                    } else {
                                        $monetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                        $otherMonetaryDetails_1 = $newFare[0]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                                    }
                                    
                                    if ($newFare[1]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                        $monetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                        $otherMonetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                                    } else {
                                        $monetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                        $otherMonetaryDetails_2 = $newFare[1]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                                    }
                                    
                                    if ($newFare[2]->fareInfoGroup->fareAmount->monetaryDetails->currency === 'INR') {
                                        $monetaryDetails_3 = $newFare[2]->fareInfoGroup->fareAmount->monetaryDetails->amount;
                                        $otherMonetaryDetails_3 = $newFare[2]->fareInfoGroup->fareAmount->otherMonetaryDetails->amount;
                                    } else {
                                        $monetaryDetails_3 = $newFare[2]->fareInfoGroup->fareAmount->otherMonetaryDetails[0]->amount;
                                        $otherMonetaryDetails_3 = $newFare[2]->fareInfoGroup->fareAmount->otherMonetaryDetails[1]->amount;
                                    }
                                @endphp
                                <div class="fontsize-17 pb-10" data-toggle="collapse" data-target="#price">Base Fare
                                </div>
                                <div class="p-1">
                                    <span class="font-14">
                                        {{ $segmentLevelGroup1[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}
                                        <i class="fa fa-inr"></i>
                                        {{ $monetaryDetails_1 . ')' }}
                                    </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_1 }}</span>
                                </div>
                                <div class="p-1">
                                    <span class="font-14">
                                        {{ $segmentLevelGroup2[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}
                                        <i class="fa fa-inr"></i>
                                        {{ $monetaryDetails_2 . ')' }}
                                    </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_2 }}</span>
                                </div>
                                <div class="p-1">
                                    <span class="font-14">
                                        {{ $segmentLevelGroup3[0]->ptcSegment->quantityDetails->unitQualifier . '(' . $newFare[2]->numberOfPax->segmentControlDetails->numberOfUnits . 'X' }}
                                        <i class="fa fa-inr"></i>
                                        {{ $monetaryDetails_3 . ')' }}
                                    </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i>
                                        {{ (int) $newFare[2]->numberOfPax->segmentControlDetails->numberOfUnits * (int) $monetaryDetails_3 }}</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="ranjepp">
                                <div class="fontsize-17 pb-10" data-toggle="collapse" data-target="#price">Fee &
                                    Surcharges
                                </div>
                                <div id="price" class="collapse show">
                                    <span class="font-14">Total Fee & Surcharges : </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i>
                                        {{ getTotalTaxes($newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits, $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits, $newFare[2]->numberOfPax->segmentControlDetails->numberOfUnits, (int) $otherMonetaryDetails_1 + $Charge, (int) $otherMonetaryDetails_2 + $Charge, (int) $otherMonetaryDetails_3, (int) $monetaryDetails_1, (int) $monetaryDetails_2, $monetaryDetails_3) }}</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="ranjepp">
                                <div class="fontsize-17 pb-10" data-toggle="collapse" data-target="#price">Other Services

                                </div>
                                <div class="collapse show">
                                    <div class="form-check ">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Charity
                                        </label>
                                        <span class="float-right fontsize-17"><i class="fa fa-inr"></i> <span
                                                id="ChaAm">10</span></span>
                                    </div>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show">
                                    <span class="font-14">Discount </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i> <span
                                            id="DisAm">0</span></span>
                                </div>
                                <div class="border-bottom"></div>
                                <div id="price" class="collapse show">
                                    <span class="font-14">Convenience fee </span>
                                    <span class="float-right fontsize-17"><i class="fa fa-inr"></i> 0</span>
                                </div>
                            </div>
                            <div class="border-bottom"></div>
                            <div class="ranjepp">
                                <div class="owstitle pb-10" data-toggle="collapse" data-target="#price1">
                                    <span class="fontsize-22"> Total Amount</span>
                                    <span class="fontsize-22 float-right"> <i class="fa fa-inr"></i>
                                        @php $totalAmount = getTotalAmount($newFare[0]->numberOfPax->segmentControlDetails->numberOfUnits, $newFare[1]->numberOfPax->segmentControlDetails->numberOfUnits, $newFare[2]->numberOfPax->segmentControlDetails->numberOfUnits, $otherMonetaryDetails_1+$Charge, $otherMonetaryDetails_2+$Charge, $otherMonetaryDetails_3); @endphp
                                        <span id="TotalFare" class="commonDesc fontWeight_600">
                                            {{ $totalAmount + 10 }}
                                        </span>

                                    </span>

                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="pb-10"></div>
                    {{-- <div class="boxunder">
                        <div class="ranjepp">
                            <div class="owstitle pb-10">Cancellation & Data change charges</div>
                        </div>
                            <div class="ranjepp">
                            <div class="onwfnt-11"><i class=""></i> Cancellation Fees Apply</div>
                            <p class="onwfnt-11">For cancellation please contact. <i class="fa fa-phone"> 08069145571</i> 
                                 </p>
                            <span class="onewflydetbtn"> VIEW POLICY </span>
                            <span class="onwfnt-11 float-right">
                                
                            </span>

                        </div>
                    </div> --}}

                    <div class="boxunder" id="boxunder">
                        <div class="card-header commonpera fontWeight_600 bg-info">HOT DEALS</div>
                        <div class="bg-white p-2">
                            <div class="form-checkk cursorPointer d-flex">
                                <input class="form-check-inputt" type="radio" name="DisAmou" id="flexRadioDefault1" value="Yes">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    <b id="DisText" class="commonpera fontWeight_600 cursorPointer">Use this coupon and get Rs 50 instant discount on your flight booking.</b>
                                </label>
                            </div>
                            <div id="remove-btn"class="form-checkk">
                                <input class="form-check-inputt" type="radio" name="DisAmou" id="flexRadioDefault2" value="No" checked>
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
                            <div class="borderbotum"></div> --}}
                        </div>

                        @if ($Agent != null)
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
                    {{-- <div class="scrollfix"> --}}
                        <h4 class="commonTitle fontWeight_600 mb-4">Itinerary</h4>

                    @if (isset($onewayNonstop))
                        @php
                            $originCountry = $onewayNonstop->itineraryDetails->originDestination->origin;
                            $destinationCountry = $onewayNonstop->itineraryDetails->originDestination->destination;
                            $marketingCompany = $onewayNonstop->itineraryDetails->segmentInformation->flightDetails->companyDetails->marketingCompany;
                            $stop = 'NO STOP';
                            $jurneyDate = getDate_fn($onewayNonstop->itineraryDetails->segmentInformation->flightDetails->flightDate->departureDate);
                            $triveltime = $otherInformation['arrivalingTime'];
                            $time1 = $onewayNonstop->itineraryDetails->segmentInformation->flightDetails->flightDate->departureTime;
                            $time2 = $onewayNonstop->itineraryDetails->segmentInformation->flightDetails->flightDate->arrivalTime;
                            $amdflightdata = $onewayNonstop->itineraryDetails->segmentInformation;
                        @endphp

                        <div class="bg-white p-2 mb-4">
                            <div class="boxunder bg-white">
                                <x-flight.segmentsection trip="DEPART" triptype="NON STOP" :segmentInformation="$onewayNonstop->itineraryDetails->segmentInformation"
                                    :itineraryDetails="$onewayNonstop->itineraryDetails" :arrivalingTime="$otherInformation['arrivalingTime']" />

                                <div class="row ranjepp py-3">
                                    <x-flight.segmentdetails :segmentInformation="$onewayNonstop->itineraryDetails->segmentInformation" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany']" />

                                </div>
                            </div>
                        </div>
                    @elseif(isset($onewayOnestop))
                        @php
                            $originCountry = $onewayOnestop->itineraryDetails->originDestination->origin;
                            $destinationCountry = $onewayOnestop->itineraryDetails->originDestination->destination;
                            $marketingCompany = $onewayOnestop->itineraryDetails->segmentInformation[0]->flightDetails->companyDetails->marketingCompany;
                            $stop = '1 STOP';
                            $jurneyDate = getDate_fn($onewayOnestop->itineraryDetails->segmentInformation[0]->flightDetails->flightDate->departureDate);
                            $triveltime = getTimeDff_fn($onewayOnestop->itineraryDetails->segmentInformation[0], $onewayOnestop->itineraryDetails->segmentInformation[1]);
                            $time1 = $onewayOnestop->itineraryDetails->segmentInformation[0]->flightDetails->flightDate->departureTime;
                            $time2 = $onewayOnestop->itineraryDetails->segmentInformation[1]->flightDetails->flightDate->arrivalTime;
                            $amdflightdata = $onewayOnestop->itineraryDetails->segmentInformation;
                        @endphp
                        <div class="bg-white p-2 mb-4">
                            <div class="boxunder">
                                <x-flight.segmentsection trip="DEPART" triptype="1 STOP" :segmentInformation="$onewayOnestop->itineraryDetails->segmentInformation[0]"
                                    :itineraryDetails="$onewayOnestop->itineraryDetails" :arrivalingTime="$otherInformation['arrivalingTime']" />

                                <div class="row py-3 gl-flightreview-card">
                                    <x-flight.segmentdetails :segmentInformation="$onewayOnestop->itineraryDetails->segmentInformation[0]" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany_1']" />

                                    <div class="col-sm-12 col_sm-121">
                                        <div class="borderbotum-2"></div>
                                        <div class="borderraduesround layorDiv">
                                            <div class="layoretext">
                                                {{ getTimeDff_fn($onewayOnestop->itineraryDetails->segmentInformation[0], $onewayOnestop->itineraryDetails->segmentInformation[1]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <x-flight.segmentdetails :segmentInformation="$onewayOnestop->itineraryDetails->segmentInformation[1]" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany_2']" />
                                </div>
                            </div>
                        </div>
                    @elseif(isset($onewayTwostop))
                        @php
                            $originCountry = $onewayTwostop->itineraryDetails->originDestination->origin;
                            $destinationCountry = $onewayTwostop->itineraryDetails->originDestination->destination;
                            $marketingCompany = $onewayTwostop->itineraryDetails->segmentInformation[0]->flightDetails->companyDetails->marketingCompany;
                            $stop = '2 STOP';
                            $jurneyDate = getDate_fn($onewayTwostop->itineraryDetails->segmentInformation[0]->flightDetails->flightDate->departureDate);
                            $triveltime = getTimeDff_fn($onewayTwostop->itineraryDetails->segmentInformation[0], $onewayTwostop->itineraryDetails->segmentInformation[1]);
                            $time1 = $onewayTwostop->itineraryDetails->segmentInformation[0]->flightDetails->flightDate->departureTime;
                            $time2 = $onewayTwostop->itineraryDetails->segmentInformation[2]->flightDetails->flightDate->arrivalTime;
                            $amdflightdata = $onewayTwostop->itineraryDetails->segmentInformation;
                        @endphp
                        <div class="pb-10">
                            <div class="boxunder">

                                <x-flight.segmentsection trip="DEPART" triptype="2 STOP" :segmentInformation="$onewayTwostop->itineraryDetails->segmentInformation[0]"
                                    :itineraryDetails="$onewayTwostop->itineraryDetails" :arrivalingTime="$otherInformation['arrivalingTime']" />

                                <div class="row ranjepp">
                                    <x-flight.segmentdetails :segmentInformation="$onewayTwostop->itineraryDetails->segmentInformation[0]" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany_1']" />

                                    <div class="col-sm-12 col_sm-121">
                                        <div class="borderbotum-2"></div>
                                        <div class="borderraduesround">

                                            {{ getTimeDff_fn($onewayTwostop->itineraryDetails->segmentInformation[0], $onewayTwostop->itineraryDetails->segmentInformation[1]) }}

                                        </div>
                                    </div>

                                    <x-flight.segmentdetails :segmentInformation="$onewayTwostop->itineraryDetails->segmentInformation[1]" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany_2']" />

                                    <div class="col-sm-12 col_sm-121">
                                        <div class="borderbotum-2"></div>
                                        <div class="borderraduesround">

                                            {{ getTimeDff_fn($onewayTwostop->itineraryDetails->segmentInformation[1], $onewayTwostop->itineraryDetails->segmentInformation[2]) }}

                                        </div>
                                    </div>

                                    <x-flight.segmentdetails :segmentInformation="$onewayTwostop->itineraryDetails->segmentInformation[2]" :arrivalingTime="$otherInformation['arrivalingTime']" :operatingCompany="$otherInformation['operatingCompany_3']" />
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="container">
                            <div id="information" class="collapse p-3 bg-info-gradient">
                                <div class="boxunder">
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
                                        <li class="fontWeight_500">Remember to download the baggage tag(s) and affix it on your bag(s).</li>
                                    </ul>
                                    <h6 class="restitleof"> <i class="fa fa-info-circle text-red"></i> State Guidelines </h6>
                                    <ul class="onwfnt-11">
                                        <li class="fontWeight_500">Check the detailed list of travel guidelines issued by Indian States and UTs.Know More</li>
                                    </ul>
                                    <h6 class="restitleof"> <i class="fa fa-info-circle text-red"></i> Baggage information </h6>
                                    <ul class="onwfnt-11">
                                        <li class="fontWeight_500">Carry no more than 1 check-in baggage and 1 hand baggage per passenger. Additional pieces of Baggage will be subject to additional charges per piece in addition to the excess baggage charges.</li>
                                    </ul>
                                    <h6 class="restitleof"> <i class="fa fa-info-circle text-red"></i> A Note on Guidelines </h6>
                                    <ul class="onwfnt-11">
                                        <li class="fontWeight_500">Disclaimer: The information provided above is only for ready reference and convenience of customers, and may not be exhaustive. We strongly recommend that you check the full text of the guidelines issued by the State Governments before travelling. Wagnistrip accepts no liability in this regard.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" id="booking-btn-section" class="collapse in ">
                        <button id="booking-btn" type="button" class="btn btn blueBtn w-100 commonTitle_1 continueres-btn" data-toggle="collapse" style=" width: 97%;" data-toggle="collapse in"> CONTINUE</button>
                    </div>
                    {{-- Travller Form Data Start --}}
                    <div id="traveller-section" class="collapse pb-20">
                        <form id="main-form" action="{{ url('/Payment/payment') }}" method="Post" data-parsley-validate>
                            @csrf
                            <input type="hidden" name="travellers" value="{{ json_encode($travellers, true) }}">
                            <input type="hidden" name="getsessions" value="{{ json_encode($getsession, true) }}">
                            <input type="hidden" name="amdflightdata" value="{{ json_encode($amdflightdata, true) }}">
                            <input type="hidden" name="otherInformations"
                                value="{{ json_encode($otherInformation, true) }}">

                            <input type="hidden" name="time1" value="{{ $time1 }}">
                            <input type="hidden" name="time2" value="{{ $time2 }}">
                            <input type="hidden" name="stop" value="{{ $stop }}">
                            <input type="hidden" name="city1" value="{{ $originCountry }}">
                            <input type="hidden" name="city2" value="{{ $destinationCountry }}">
                            <input type="hidden" name="triveltime" value="{{ $triveltime }}">
                            @php
                                session(['total_fare' => $totalAmount]);
                            @endphp
                            <input type="hidden" name="fare" value="{{ $totalAmount }}">
                            <input type="hidden" id="Chari"name="Chari">
                            <input type="hidden" id="textDis"name="textDis" value="no">
                            <input type="hidden" name="trip" value="Oneway">
                            {{-- <br>
                           <span class="searchtitle">Enter code*</span>
                            <input type="text" name="Code" class="form-control" placeholder="Enter code" value="{{ $totalAmount }}" />
                            <br> --}}
                            <x-flight.travellerform :travellers="$travellers" :originCountry="$originCountry" :destinationCountry="$destinationCountry"
                                :jurneyDate="$jurneyDate" />
                        </form>

                    </div>
                    {{-- Travller Form Data End --}}
                    {{-- </div> --}}
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
        $(document).ready(function() {

            {{-- /////// API Data code By Neelesh ////// --}}

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

            ///////  End  Code By Neelesh //////



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
            $('#travller-btn').on('click', function() {
                $('#main-form').submit();
            });
        });
    </script>
    <script>
        let checkbox = document.getElementById("flexCheckChecked");
        let DisAmBox = document.getElementById("flexRadioDefault1");
        let DisText = document.getElementById("DisText");
        let text = document.getElementById("Chari");
        let textDis = document.getElementById("textDis");
        let TotalFare = document.getElementById("TotalFare");
        let ChaAm = document.getElementById("ChaAm");
        let DisAm = document.getElementById("DisAm");
        let removrBtn = document.getElementById("remove-btn");
        checkbox.addEventListener("change", () => {
            // console.log(TotalFare , TotalFare.innerText);
            if (checkbox.checked) {
                text.value = "yes";
                TotalFare.innerText = parseInt(TotalFare.innerText) + 10;
                ChaAm.innerText = parseInt(ChaAm.innerText) + 10;
            } else {
                text.value = "no";
                TotalFare.innerText = parseInt(TotalFare.innerText) - 10;
                ChaAm.innerText = parseInt(ChaAm.innerText) - 10;
            }
        });

        removrBtn.style.display = "none";
        $('input[name=DisAmou]').on('change', function() {
            let Disvalue = $(this).filter(':checked').val();
            if (Disvalue == 'Yes') {
                textDis.value = "yes";
                DisAm.innerText = parseInt(DisAm.innerText) + 50;
                TotalFare.innerText = parseInt(TotalFare.innerText) - 50;
                DisText.innerText = "Congratulations! Promo Discount of Rs. 50 applied successfully.";
                DisText.style.color = "green";
                removrBtn.style.display = "block";
                DisText.style.display = "block";
                // console.log($(this).filter(':checked').val() , Disvalue);
            } else {
                textDis.value = "no";
                DisAm.innerText = parseInt(DisAm.innerText) - 50;
                TotalFare.innerText = parseInt(TotalFare.innerText) + 50;
                DisText.innerText = "Use this coupon and get Rs 50 instant discount on your flight booking.";
                DisText.style.color = "black";
                removrBtn.style.display = "none";
                removrBtn.style.color = "black";
            }
            // console.log($(this).filter(':checked').val() , Disvalue);
        });
    </script>

    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {

                // Handle page restore.
                window.location.reload();
            }
        });
    </script>

@stop
@endsection
