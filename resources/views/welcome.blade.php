@extends('layouts.master')
@section('title', 'Find Cheap Flights & Airline Tickets | Book Your Next Trip Now')
@section('Description', 'Explore the best flight deals & book your next trip with ease. Discover low fares and save on
    airline tickets')
@section('body')
    <section class="flightbanner">
        <div id="carouselExampleSlidesOnly" class="carousel slide hide-elements-in-mb" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="imgfrontbanner_1 commonBannerImage">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="imgfrontbanner_2 commonBannerImage">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="imgfrontbanner_3 commonBannerImage">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="imgfrontbanner_4 commonBannerImage">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="imgfrontbanner_5 commonBannerImage">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="imgfrontbanner_6 commonBannerImage">
                    </div>
                </div>
            </div>
        </div>

        <div class="container flightsearchbox">
            <div class="searcbox-inner rounded-10">
                <form id="main-form" action="{{ route('search-flight-results') }}" method="get">
                    <div class="topradioflexdif row justify-content-between">
                        <div class="flightsearchboxradiobtns d-flex" id="radio_box-1">
                            <p class="radiobtn">
                                <input type="radio" name="trip-type" class="trip-typ" id="oneway-btn" value="oneway" checked="checked">
                                <label for="oneway-btn" class="commonTitle_1 fontWeight_600" id="oneway"> Oneway</label>
                            </p>
                            <p class="radiobtn">
                                <input type="radio" name="trip-type" class="trip-typ" id="roundtrip-btn" value="roundtrip">
                                <label for="roundtrip-btn" class="commonTitle_1 fontWeight_600" id="round">Round Trip</label>
                            </p>
                            {{-- <p class="radiobtn">
                            <input type="radio" id="optradio" class="trip-type" name="trip-type" value="multicity">
                            <label for="optradio"  class="commonTitle_1 fontWeight_600"id="multic"> Multicity </label>
                            </p> --}}
                        </div>
                        <span class="float-right searchflighttoptext hide-elements-in-mb">
                            <i class="fa fa-plane"></i>
                            <a href="javascript:void(0)" class="commonDesc color-blue fontWeight_500">Search Lowest Airfare </a> |
                            <a href="javascript:void(0)" class="commonDesc color-blue fontWeight_500"> International Flights </a> |
                            <a href="javascript:void(0)" class="commonDesc color-blue fontWeight_500"> Domestic Flights</a>
                        </span>
                    </div>
                    <div class="d-flex searchinputfields">
                        <div class="departure-from">
                            <div class="commonDesc fontWeight_500"> <i class="fa fa-plane"></i> FROM</div>
                            <strong class="cityname commonHeading ellipsisHeading d-block color-blue">Delhi</strong>
                            <span class="d-block airportname ellipsisHeading" onmouseover="showairportnameFrom()" onmouseout="hideairportname()">DEL, Indira Gandhi International Airport India</span>
                            <span class="showairportnameonoverFrom mouseovertext" style="display: none"></span>
                            <input type="hidden" name="departure" value="DEL" id="departureFrom">
                            <div class="dropdownlistmaindiv shadow">
                                <div class="airportsearchdiv d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="15" height="15" x="0" y="0" viewBox="0 0 612.01 612.01" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M606.209 578.714 448.198 423.228C489.576 378.272 515 318.817 515 253.393 514.98 113.439 399.704 0 257.493 0S.006 113.439.006 253.393s115.276 253.393 257.487 253.393c61.445 0 117.801-21.253 162.068-56.586l158.624 156.099c7.729 7.614 20.277 7.614 28.006 0a19.291 19.291 0 0 0 .018-27.585zM257.493 467.8c-120.326 0-217.869-95.993-217.869-214.407S137.167 38.986 257.493 38.986c120.327 0 217.869 95.993 217.869 214.407S377.82 467.8 257.493 467.8z" fill="#000000" data-original="#000000" class=""></path></g></svg>
                                    <input class="airportsearchField" onkeyup="filterairportlist(0)" type="text" placeholder="From">
                                </div>
                                <ul class="airpordroplist" id="airpordroplistfrom">
                                    {{-- <li class="lists d-flex align-items-center" airpor-cityname="Delhi">
                                        <img src="../../assets/images/common/flighticon.png" alt="">
                                        <div class="airportnametexts">
                                            <span class="citynameinner">Delhi</span>
                                            <p class="airportnameinnner ellipsisHeading d-block">DEL, Indira Gandhi International Airport India</p>
                                        </div>
                                    </li>
                                     --}}

                                </ul>
                            </div>

                            {{-- <select class="js-example-basic-single getLocation" name="departure"
                            id="select2_departure" data-next="#select2_arrival" >
                            <option value="DEL">Delhi (DEL)</option>
                            </select> --}}
                            {{-- <select class="js-example-basic-single getLocation" name="departure"
                                data-next="#select2_arrival" id="select2_departure">
                                <option value="DEL" class="departureFromPlace">Delhi (DEL)</option>
                            </select> --}}
                        </div>

                        <div class="transferdata cursorPointer" id="transfetairpors">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 6.35 6.35" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M1.849.53a.264.264 0 0 0-.182.08L.61 1.665a.264.264 0 0 0 0 .375l1.057 1.057a.264.264 0 1 0 .373-.374l-.605-.605h4.117a.264.264 0 0 0 0-.529h-4.12L2.04.983A.264.264 0 0 0 1.849.53zm2.646 2.642a.264.264 0 0 0-.183.455l.604.604H.8a.264.264 0 1 0 0 .529h4.12l-.607.607a.264.264 0 1 0 .374.372l1.057-1.055a.264.264 0 0 0 0-.375L4.686 3.252a.264.264 0 0 0-.191-.08z" fill="#000000" data-original="#000000" class=""></path></g></svg>
                        </div>

                        <div class="departure-to">
                            <div class="commonDesc fontWeight_500"> <i class="fa fa-plane toplane rotate-90"></i> TO</div>
                            <strong class="cityname commonHeading ellipsisHeading d-block color-blue">Mumbai</strong>

                            <span class="d-block airportname ellipsisHeading" onmouseover="showairportnameTo()" onmouseout="hideairportname()">BOM, Chhatrapati Shivaji International Airport India</span>
                            <span class="showairportnameonoverTo mouseovertext" style="display: none"></span>
                            <input type="hidden" name="arrival" value="BOM" id="arrivalTo">
                            <div class="dropdownlistmaindiv shadow">
                                <div class="airportsearchdiv d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="15" height="15" x="0" y="0" viewBox="0 0 612.01 612.01" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M606.209 578.714 448.198 423.228C489.576 378.272 515 318.817 515 253.393 514.98 113.439 399.704 0 257.493 0S.006 113.439.006 253.393s115.276 253.393 257.487 253.393c61.445 0 117.801-21.253 162.068-56.586l158.624 156.099c7.729 7.614 20.277 7.614 28.006 0a19.291 19.291 0 0 0 .018-27.585zM257.493 467.8c-120.326 0-217.869-95.993-217.869-214.407S137.167 38.986 257.493 38.986c120.327 0 217.869 95.993 217.869 214.407S377.82 467.8 257.493 467.8z" fill="#000000" data-original="#000000" class=""></path></g></svg>
                                    <input class="airportsearchField" onkeyup="filterairportlist(1)" type="text" placeholder="From">
                                </div>
                            </div>
                            <div class="city-msg mt-2" id="cisty_mesg-12"></div>
                        </div>

                        <div class="calenderbox departure">
                            <div id="id_startCalendar" class="calendar-widget default-today" data-next="#id_deadlineCalendar" date-min="today" tabindex="-1">
                                <div class="input-wrapper">
                                    <div class="commonDesc fontWeight_500 d-flex align-items-center"> DEPARTURE <svg xmlns="http://www.w3.org/2000/svg" style="margin-left:8px;" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="16" height="16" x="0" y="0" viewBox="0 0 34 34" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M29.6 2h-3v3c0 .6-.5 1-1 1s-1-.4-1-1V2h-16v3c0 .6-.5 1-1 1s-1-.4-1-1V2h-3C2.1 2 1 3.3 1 5v3.6h32V5c0-1.7-1.8-3-3.4-3zM1 10.7V29c0 1.8 1.1 3 2.7 3h26c1.6 0 3.4-1.3 3.4-3V10.7zm8.9 16.8H7.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8H10c.4 0 .8.3.8.8v2.5c-.1.5-.4.8-.9.8zm0-9H7.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8H10c.4 0 .8.3.8.8v2.5c-.1.5-.4.8-.9.8zm8 9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm0-9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm8 9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm0-9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8z" fill="#212529" data-original="#000000" class=""></path></g></svg> </div>
                                    <input class="date-field" id="type1-start" type="text" placeholder="DEPARTURE" name='departDate' readonly>
                                </div>
                                <div class="calendar-wrapper" id="calender_flight-12">
                                    <div class="dual-calendar">
                                        <div class="calendar">
                                            <div class="calendar-header">
                                                <div class="prev-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                                                <div class="month-text"><p>September 2018</p></div>
                                            </div>
                                            <div class="calendar-body">
                                                <div class="date-table">
                                                    <div class="date-table-header">
                                                        <div class="day sunday">S</div>
                                                        <div class="day">M</div>
                                                        <div class="day">T</div>
                                                        <div class="day">W</div>
                                                        <div class="day">T</div>
                                                        <div class="day">F</div>
                                                        <div class="day saturday">S</div>
                                                    </div>
                                                    <div class="date-table-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="calendar plus-one">
                                            <div class="calendar-header">
                                                <div class="month-text">
                                                    <p>September</p>
                                                </div>

                                                <div class="next-btn">
                                                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="calendar-body">
                                                <div class="date-table">
                                                    <div class="date-table-header">
                                                        <div class="day sunday">S</div>
                                                        <div class="day">M</div>
                                                        <div class="day">T</div>
                                                        <div class="day">W</div>
                                                        <div class="day">T</div>
                                                        <div class="day">F</div>
                                                        <div class="day saturday">S</div>
                                                    </div>
                                                    <div class="date-table-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="calenderbox return">
                            <div id="id_deadlineCalendar" class="calendar-widget default-today-return" tabindex="-1" data-link="#id_startCalendar" date-min="link">
                                <div class="input-wrapper" id="checkreturnradio">
                                    <div class="commonDesc fontWeight_500 d-flex align-items-center">RETURN  <svg xmlns="http://www.w3.org/2000/svg" style="margin-left:8px;" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="16" height="16" x="0" y="0" viewBox="0 0 34 34" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M29.6 2h-3v3c0 .6-.5 1-1 1s-1-.4-1-1V2h-16v3c0 .6-.5 1-1 1s-1-.4-1-1V2h-3C2.1 2 1 3.3 1 5v3.6h32V5c0-1.7-1.8-3-3.4-3zM1 10.7V29c0 1.8 1.1 3 2.7 3h26c1.6 0 3.4-1.3 3.4-3V10.7zm8.9 16.8H7.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8H10c.4 0 .8.3.8.8v2.5c-.1.5-.4.8-.9.8zm0-9H7.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8H10c.4 0 .8.3.8.8v2.5c-.1.5-.4.8-.9.8zm8 9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm0-9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm8 9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8zm0-9h-2.5c-.4 0-.8-.3-.8-.8v-2.5c0-.4.3-.8.8-.8h2.5c.4 0 .8.3.8.8v2.5c0 .5-.3.8-.8.8z" fill="#212529" data-original="#000000" class=""></path></g></svg> </div>
                                    <input class="date-field" id="type1-deadline" name="returnDate" type="text" placeholder="RETURN" readonly>
                                    <span id="onetripmsg" class="msggggk active fontWeight_500">Tap to add a return date for bigger discount</span>
                                </div>
                                <div class="calendar-wrapper" id="closediv1">
                                    <div class="dual-calendar abddjd">
                                        <div class="calendar">
                                            <div class="calendar-header">
                                                <div class="prev-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></div>
                                                <div class="month-text"><p>September 2018</p></div>
                                            </div>
                                            <div class="calendar-body">
                                                <div class="date-table">
                                                    <div class="date-table-header">
                                                        <div class="day sunday">S</div>
                                                        <div class="day">M</div>
                                                        <div class="day">T</div>
                                                        <div class="day">W</div>
                                                        <div class="day">T</div>
                                                        <div class="day">F</div>
                                                        <div class="day saturday">S</div>
                                                    </div>
                                                    <div class="date-table-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="calendar plus-one">
                                            <div class="calendar-header">
                                                <div class="month-text">
                                                    <p>September</p>
                                                </div>

                                                <div class="next-btn">
                                                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="calendar-body">
                                                <div class="date-table">
                                                    <div class="date-table-header">
                                                        <div class="day sunday">S</div>
                                                        <div class="day">M</div>
                                                        <div class="day">T</div>
                                                        <div class="day">W</div>
                                                        <div class="day">T</div>
                                                        <div class="day">F</div>
                                                        <div class="day saturday">S</div>
                                                    </div>
                                                    <div class="date-table-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <span class="datebtncloserounde"> <i class="fa fa-times-circle"></i> </span> --}}
                        </div>

                        <div class="selectBox">
                            <div class="selecttoggeldiv" onclick="togglePopup()">
                                <div class="commonDesc fontWeight_500">Travellers & class <i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                                <div class="commonDesc fontWeight_500" id="total-travller"></div>
                                <div class="commonDesc fontWeight_500" id="cabClass"></div>
                            </div>
                            <div class="travllerBox content shadow rounded-10" style="display: none">
                                <div class="card-body">
                                    <div class="commonDesc mb-1 fontWeight_600">ADULTS(12y+)</div>
                                    <div class="card-body border">
                                        <label class="rdocon">
                                            <input type="radio" checked="checked" name="noOfAdults" value="1">
                                            <span class="checkmark">1</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="2">
                                            <span class="checkmark">2</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="3">
                                            <span class="checkmark">3</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="4">
                                            <span class="checkmark">4</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="5">
                                            <span class="checkmark">5</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="6">
                                            <span class="checkmark">6</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="7">
                                            <span class="checkmark">7</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="8">
                                            <span class="checkmark">8</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfAdults" value="9">
                                            <span class="checkmark">9</span>
                                        </label>
                                    </div>
                                    <div class="commonDesc mb-1 fontWeight_600">CHILREN(2y - 12y)</div>
                                    <div class="card-body border">
                                        <label class="rdocon">
                                            <input type="radio" checked="checked" name="noOfChilds"
                                                value="0">
                                            <span class="checkmark">0</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="1">
                                            <span class="checkmark">1</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="2">
                                            <span class="checkmark">2</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="3">
                                            <span class="checkmark">3</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="4">
                                            <span class="checkmark">4</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="5">
                                            <span class="checkmark">5</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="6">
                                            <span class="checkmark">6</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfChilds" value="7">
                                            <span class="checkmark">6+</span>
                                        </label>
                                    </div>
                                    <div class="commonDesc mb-1 fontWeight_600">INFANTS(below 2y)</div>
                                    <div class="card-body border border-bottom-0">
                                        <label class="rdocon">
                                            <input type="radio" checked="checked" name="noOfInfants"
                                                value="0">
                                            <span class="checkmark">0</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="1">
                                            <span class="checkmark">1</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="2">
                                            <span class="checkmark">2</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="3">
                                            <span class="checkmark">3</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="4">
                                            <span class="checkmark">4</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="5">
                                            <span class="checkmark">5</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="6">
                                            <span class="checkmark">6</span>
                                        </label>
                                        <label class="rdocon">
                                            <input type="radio" name="noOfInfants" value="7">
                                            <span class="checkmark">6+</span>
                                        </label>
                                    </div>
                                    <div class="card-body border trvallerclasstype">
                                        <div class="commonDesc fontWeight_600 pb-2">CHOOSE TRAVEL CLASS</div>
                                        <div class="classFlexdiv d-flex justify-content-between align-items">
                                            <label class="rdocon">
                                                <input type="radio" name="cabinClass" value="Y" checked="checked">
                                                <span class="checkmark-2 commonpera">Economy</span>
                                            </label>
                                            <label class="rdocon">
                                                <input type="radio" name="cabinClass" value="p">
                                                <span class="checkmark-2 commonpera">Premium Economy</span>
                                            </label>
                                            <label class="rdocon">
                                                <input type="radio" name="cabinClass" value="C">
                                                <span class="checkmark-2 commonpera">Bussiness</span>
                                            </label>
                                            <label class="rdocon">
                                                <input type="radio" name="cabinClass" value="F">
                                                <span class="checkmark-2 commonpera">First Class</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="msgandapplydiv py-2 d-flex justify-content-between align-items">
                                        <div class="msg-travllers"></div>
                                        <button
                                            type="button"
                                            id="travller-btn"
                                            class="btn blueBtn"
                                            onclick="togglePopup()"
                                        >Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id='fare4' name="fare" value="Regular Fares">

                    <div class="d-flex align-items-center flightfilterbyfare hide-elements-in-mb">
                        <button
                            type="button"
                            id="regular1"
                            class="btn btn-sm btn-outline-info bshadow btn1 active"
                            value="Regular Fares"
                            >Regular Fares</button>
                        <button
                            type="button"
                            id="regular2"
                            class="btn btn-sm btn-outline-info bshadow btn1"
                            >Armed Forces Fares </button>
                        <button
                            type="button"
                            id="regular3"
                            class="btn btn-sm btn-outline-info bshadow btn1"
                            > Students Fares </button>
                        <button
                            type="button"
                            id="regular4"
                            class="btn btn-sm btn-outline-info bshadow btn1"
                            >Senior Citizen Fares</button>
                        <a
                            href="{{ route('web-check-in') }}"
                            class="btn btn-primary webcheck link-color"
                            data-loading="true"> WEB CHECKIN ABC</a>
                    </div>

                    <div class="bannerTextLine rounded-5">
                        <span class="commonDesc fontWeight_600">GET Rs 2000 ON SIGN UP AND ENJOY CASH BACK OFFERS ON YOUR ALL BOOKING !!!</span>
                        <a href="javascript:void(0);" class="float-right commonDesc fontWeight_600" data-toggle="modal" data-target="#staticBackdrop" fdprocessedid="ymkjep"><strong>SIGN UP | LOGIN NOW </strong><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                    </div>

                    <div class="text-center pt-10 pb-10">
                        <a class="searchbtnFlight btn blueBtn color-white commonTitle_1" id="main-search-btn" onclick="spinnerr()">SEARCH <i class="fa fa-search" aria-hidden="true"></i></a>
                    </div>

                </form>
            </div>
        </div>

    </section>


    <div class="loaderr posotionFixed" style="display: none">
        <div class="loading d-flex justify-content-center align-items-center">
            <div class="contentdiv rounded-10">
                <img src="assets/images/loader.gif" alt="" srcset="" loading="lazy">
                <p class="commonTitle fontWeight_500">Please have patience,<br>Your flight will be searched soon</p>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function spinnerr(loading) {
            document.getElementsByClassName("loaderr")[0].style.display = "block";
        };
    </script>

    {{-- <div class="container impTextHome w-fitcontent bg-white rounded-10">
        <div class="textdiv commonDesc">
            <b class="fontWeight_600 commonDesc">Important Info:</b> To cancel/claim refund or reschedule/modify your booking. <a href="https://www.wagnistrip.com/contact" class="commonDesc">Click here</a>
        </div>
    </div> --}}


    {{-- this section display none --}}
    <x-frontend.sections.offers-section-one />

    <div class="container popularflightslider" style="">
        <div class="commonWhiteBg">
            <div class="d-flex align-items-center justify-content-between btnssreser">
                <h2 class="commonHeading font-weight-bold">Popular Flight Routes</h2>
                <div class="offersButtons_12 sliderbtnsDiv">
                    <span href="#flightTopList_1" role="button" data-slide="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span href="#flightTopList_1" role="button" data-slide="next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </div>
            </div>
            <div id="flightTopList_1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-sm-4">
                                <a id="deltojaipur" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/Jaipur.png" height="200" alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO JAIPUR</p>
                                                    {{--    <p class="m-0 float-right">Starting: ₹ 1997</p>  --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{--   <p class="m-0 text-secondary float-left small proute">DELHI TO JAIPUR</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2023</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a id="deltoamritsar" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/Amritsar.png" height="200" alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO AMRITSAR</p>
                                                    {{--  <p class="m-0 float-right">Starting: ₹ 3224</p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{-- <p class="m-0 text-secondary float-left small proute">DELHI TO AMRITSAR</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2023</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4 p-2">
                                <a id="deltoahmedabad" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/Ahmedabad.png" height="200" alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO AHMEDABAD</p>
                                                    {{-- <p class="m-0 float-right">Starting: ₹ 2594</p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{--  <p class="m-0 text-secondary float-left small proute">DELHI TO AHMEDABAD</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2022</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row p-2">
                            <div class="col-sm-4 p-2">
                                <a id="deltochandigarh" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/Chandigarh.png" height="200"
                                            alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO CHANDIGARH</p>
                                                    {{-- <p class="m-0 float-right">Starting: ₹ 2803</p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{--  <p class="m-0 text-secondary float-left small proute">DELHI TO CHANDIGARH</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2022</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4 p-2">
                                <a id="delhtolucknow" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/Lucknow.png" height="200"
                                            alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO LUCKNOW</p>
                                                    {{-- <p class="m-0 float-right">Starting: ₹ 2699</p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{--    <p class="m-0 text-secondary float-left small proute">DELHI TO LUCKNOW</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2022</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-sm-4 p-2">
                                <a id="deltokanpur" href="#">
                                    <div class="card text-left">
                                        <img class="card-img-top" src="assets/images/flights/kanpur.png" height="200"
                                            alt="" loading="lazy">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p class=" commonTitle_1 text-center">DELHI TO PUNE</p>
                                                    {{-- <p class="m-0 float-right">Starting: ₹3581</p> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    {{--    <p class="m-0 text-secondary float-left small proute">DELHI TO PUNE</p> --}}
                                                    <!--<p class="m-0 float-right small  text-secondary">04/04/2022</p>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- this section display none --}}
    <x-frontend.sections.top-destinations-holidays />

    <div class="container FlightRoutesContainer">
        <div class="d-flex flex-wrap-in-mb justify-content-between flightroutrow">
            <div class="col-sm-6 colflightrout p-0">
                <div class="card p-3">
                    <div class="text-center font-weight-bold commonTitle">Popular Domestic Flights</div>
                    <table class="table table-bordered mt-4 text-center">
                        <tbody>
                            <tr>
                                <td class="commonDesc">Hyderabad to Bangalore (HYD-BLR)</td>
                                <td><a id="hydtobang" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Mumbai to Chennai (BOM-MAA)</td>
                                <td><a id="mumtochen" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Chennai to Mumbai (MAA-BOM)</td>
                                <td><a id="chentomum" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Delhi to Ahmedabad (DEL-AMD)</td>
                                <td><a id="deltoahm" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Mumbai to Kolkata (BOM-CCU)</td>
                                <td><a id="mumtokol" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6 colflightrout p-0">
                <div class="card p-3">
                    <div class="text-center font-weight-bold commonTitle">Popular International Flights</div>
                    <table class="table table-bordered mt-4 text-center">

                        <tbody>
                            <tr>
                                <td class="commonDesc">Chandigarh To Bangkok (IXC-BKK)</td>
                                <td> <a id="goatomum" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Mumbai to Bangkok (BOM-BKK)</td><td>
                                    <a id="mumtobang" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Mumbai to Dubai (BOM-DXB)</td>
                                <td><a id="mumtodub" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Kolkata to Malaysia (CCU-KUL)</td>
                                <td> <a id="koltomal" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                            <tr>
                                <td class="commonDesc">Delhi to Singapore (DEL-SIN)</td>
                                <td><a id="deltosin" href="#" class="btn commonDesc btn-primary btn-sm">Search Now</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="container FlightSeotextdiv">
        <div class="d-flex flex-wrap-in-mb justify-content-between flightseotextrow">
            <div class="col-sm-6 colflightseotext p-0">
                <div class="card p-4">
                    <table class="table table-bordered text-center">
                        <tr>
                            <th class="border-bottom-0">
                                <h6 class="commonDesc font-weight-bold">Why to Book Domestic Air Tickets from www.wagnistrip.com?</h6>
                            </th>
                        </tr>
                        <tbody>
                            <tr>
                                <td>
                                    <p class="text-justify">Wagnistrip brings new offers on various travel products along with flight tickets. So, proceed for grabbing the best deals on domestic flight tickets with other travel solutions. The Indian aviation industry is witnessing a boom in the domestic airline sector due to the increase in air travelers. At www.wagnistrip.com, you can search and book various domestic airlines including, Air India, GO FIRST, Indian Airlines, IndiGo, SpiceJet, Vistara, and more to enjoy good connectivity to different Indian cities. It doesn’t charge any convenience fees or hidden charges and offers the lowest rates on flight booking. If you have to book a domestic flight in the coming days, don’t go anywhere just visit our flight page.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6 colflightseotext p-0">
                <div class="card p-4">
                    <table class="table table-bordered text-center">
                        <tr>
                            <th>
                                <h6 class="commonDesc font-weight-bold">Why to Book International Air Tickets from www.wagnistrip.com?</h6>
                            </th>
                        </tr>
                        <tbody>
                            <tr>
                                <td>
                                    <p class="text-justify">Visit all the world's biggest cities by purchasing affordable international airline tickets at www.wagnistrip.com. We provide you the widest selection of airlines flying to various nations while guaranteeing an enjoyable trip. Use wagnistrip.com to reserve any international flight. There are numerous benefits to reserving with us when travelling abroad: Leading company with a solid reputation, offering a variety of ticket alternatives, and having a strong relationship with global airline networks. The finest deals on all significant travel routes are guaranteed, and it aids you in selecting the budget international flights. Order inexpensive airline tickets for all popular international destinations in a few easy steps. </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <x-frontend.sections.maketruetripoffer /> --}}

    <div class="container flight_sliderOffers mobileVes1 mt-5 mb-5 FlightBlog">
        <div class="commonWhiteBg">
            <h2 class="font-weight-bold commonHeading">Travel Blogs</h2>
            <div class="card-deck">
                <div class="card">
                    <img class="card-img-top" src="assets/images/blog/blod-1.jpg" alt="Card image cap">
                    <div class="card-body">
                        <div class="commonDesc font-weight-bold ">Kedarnath Yatra</div>
                        <p class="card-text">Regardless of how difficult the objective of Kedarnath is, the profound tornado and the feel of the spot are so compelling that lovers can't help but come here for Lord Shiva's divine elegance. It is considered one of the Char Dhams...<a href="https://www.wagnistrip.com/blog-details">Read More</a></p>
                        <small class="text-muted">Last updated 2 months ago</small>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="assets/images/blog/blog-2.jpg" alt="Card image cap">
                    <div class="card-body">
                        <div class="commonDesc font-weight-bold">Guides and Travel Tips for Thailand.</div>
                        <p class="card-text">We've put together the top Thailand tours and advice here. This Thailand guide and travel blog brings all of our top Thailand travel tips, information tales, experiences, and stories together. There are travel tips for singles and couples as well, not just families...<a href="https://www.wagnistrip.com/blog-details2">Read More</a></p>
                        <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="assets/images/blog/blog-3.jpg" alt="Card image cap">
                    <div class="card-body">
                        <div class="commonDesc font-weight-bold ">Nainital is one of Uttarakhand's most well known hill station.</div>
                        <p class="card-text">Clearly based on the lines of the Cumbrian Lake District by the British, Nainital is one of Uttarakhand's most well-known hill stations. Nainital is set in a valley around an eye-shaped lake, where, according to legend...<a href="https://www.wagnistrip.com/blog-details3">Read More</a></p>
                        <small class="text-muted">Last updated 3 months ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--//////////////////////////////////////////////////////////////////////////////////////////////-->

    <div class="container-fluid bg-white">
        <div class="container">
            <div class="row p-2">
                <div class="col-sm-4 pt-1">
                    <h6 class="font-weight-bold commonTitle">Why Wagnistrip?</h6>
                    <hr class="my-2">
                    <p class="commonpera fontWeight_500 text-justify">
                        Wagnistrip is an online travel company. It is well known for providing our customers with the best
                        travel offers. The staff at Wagnistrip has been pushed by vision and tenacity to succeed. With a
                        strong commitment and plenty of hard work, Wagnistrip has expanded its product line in advance.
                        offering, which contains a range of offline and online goods and services. Therefore, begin your
                        search for the cheapest flights right now.</p>
                </div>
                <div class="col-sm-4 pt-1">
                    <h6 class="font-weight-bold commonTitle ">We are offering cheapest flight.</h6>
                    <hr class="my-2">
                    <p class="commonpera fontWeight_500 text-justify">
                        Wagnistrip provides the greatest prices on airline tickets, hotel accommodations, holiday packages,
                        and a variety of other travel products. Our objective is to offer our customers the greatest and
                        most economical ticket booking options. Wagnistrip will also offer hotel deals and a variety of
                        other travel services throughout India. When traveling abroad, there are several advantages to
                        booking with us, including our position as a preeminent firm with a good reputation.</p>
                </div>
                <div class=" col-sm-4 pt-1">
                    <h6 class="font-weight-bold commonTitle">We are offering Holidays packages.</h6>
                    <hr class="my-2">
                    <p class="commonpera fontWeight_500 text-justify">
                        Wagnistrip provides fantastic deals on vacation packages all around India and internationally.
                        Wagnistrip offers all-inclusive vacation packages to a variety of foreign places.
                        You will also receive a number of customised tours. Do compare our rates with other travel portals
                        and be prepared to receive the greatest deals (put this portion first) So, call us soon and we
                        welcome you all to book some memorable holidays with us.</p>
                </div>

            </div>
        </div>
    </div>


    <div class="offerPopup" style="display: none">
        <div class="flexDivPopup">
            <div class="contentDiv">
                <div class="closeOfferPopup">
                    <span>×</span>
                </div>
                <div class="offerImageBox">
                    <img src="{{ url('assets/images/tempimages/offerimage.jpg') }}" alt"">
                </div>
            </div>
        </div>
    </div>



    <div class="setCookiesDiv" style="display: none">
        <div class="contentDiv container">
            <p>Clicking "Accept all cookies" authorizes Stack Exchange to place cookies on your computer or any other device
                and to share information with third parties in accordance with our cookie policy.</p>
            <div class="flexBtns">
                <button class="cookies-btnbtn">Necessary</button>
                <button class="cookies-btnbtn">Accept All</button>
                <button class="cookies-btnbtn">Close</button>
            </div>
        </div>
    </div>

    <div class="dpnr">
        <x-footer />
    </div>



    <script defer src="{{url('assets/js/welcomeblade.js')}}"></script>

    <script>

        // == == start  script for flight search == == //

        async function getairportList(departrefrom) {
            let searchairport = document.getElementsByClassName('airportsearchField')[departrefrom].value;
            const response = await fetch(`api/airlinecodes?search=${searchairport}`);
            const resjson = await response.json();
            let airpordroplistFrom = document.getElementsByClassName('airpordroplist');


            let htmllist = '';
            resjson.forEach(element => {
                htmllist += `<li class="lists d-flex align-items-center" airportcode="${element.id}" airpor-cityname="${element.head}" airpor-fullname="${element.text}"> <img src="../../assets/images/common/flighticon.png" alt=""> <div class="airportnametexts"> <span class="citynameinner fontWeight_600">${element.head}</span> <p class="airportnameinnner ellipsisHeading d-block">${element.text}</p> </div>`;
            });
            airpordroplistFrom[departrefrom].innerHTML = (htmllist);
        }

        function filterairportlist(departrefrom) {
            getairportList(departrefrom);
        }

        $('.departure-from').on('click', function () {
            $('.airportsearchField').focus();
            getairportList(0);
            $('.departure-from .dropdownlistmaindiv').show();
            $(document).bind("click", function (event) {
                $target = $(event.target);
                if (!$target.closest(".departure-from").length &&
                    $(".departure-from .dropdownlistmaindiv").is(":visible")) {
                    $(".departure-from .dropdownlistmaindiv").hide();
                    $(document).unbind("click", function (event) { });
                }
            });
        });

        $('.departure-to').on('click', function () {
            $('.airportsearchField').focus();
            getairportList(1);
            $('.departure-to .dropdownlistmaindiv').show();
            $(document).bind("click", function (event) {
                $target = $(event.target);
                if (!$target.closest(".departure-to").length &&
                    $(".departure-to .dropdownlistmaindiv").is(":visible")) {
                    $(".departure-to .dropdownlistmaindiv").hide();
                    $(document).unbind("click", function (event) { });
                }
            });
        });

        $(document).on("click", ".departure-from li", function () {
            let getcityname = $(this).attr("airpor-cityname");
            let getairportname = $(this).attr("airpor-fullname");
            let airportcode = $(this).attr("airportcode");
            $(".departure-from .cityname").html(getcityname);
            $(".departure-from .airportname ").html(getairportname);
            $("#departureFrom").val(airportcode);
            $('.dropdownlistmaindiv').hide();
        });

        $(document).on("click", ".departure-to li", function () {
            let getcityname = $(this).attr("airpor-cityname");
            let getairportname = $(this).attr("airpor-fullname");
            let airportcode = $(this).attr("airportcode");
            $(".departure-to .cityname").html(getcityname);
            $(".departure-to .airportname").html(getairportname);
            $("#arrivalTo").val(airportcode);
            $('.dropdownlistmaindiv').hide();
        });


        $(document).on('click', '#transfetairpors', function () {
            let departurecity = $(".departure-from .cityname").text();
            let departureairport = $(".departure-from .airportname").text();
            let departurehdnval = $("#departureFrom").val();

            let arrivalcity = $(".departure-to .cityname").text();
            let arrivalairport = $(".departure-to .airportname").text();
            let arrivalhdnval = $("#arrivalTo").val();

            $(".departure-from .cityname").text(arrivalcity);
            $(".departure-from .airportname").text(arrivalairport);
            $("#departureFrom").val(arrivalhdnval);

            $(".departure-to .cityname").text(departurecity);
            $(".departure-to .airportname").text(departureairport);
            $("#arrivalTo").val(departurehdnval);
        });

        function showairportnameFrom(){
            let airportnametextFrom = $('.departure-from .airportname').text();
            let showairportnameonoverFrom = $('.showairportnameonoverFrom');
            showairportnameonoverFrom.css('display','block')
            showairportnameonoverFrom.html(airportnametextFrom);
        }

        function showairportnameTo(){
            let airportnametextTo = $('.departure-to .airportname').text();
            let showairportnameonoverTo = $('.showairportnameonoverTo');
            showairportnameonoverTo.css('display','block')
            showairportnameonoverTo.html(airportnametextTo);
        }

        function hideairportname(){
            $('.showairportnameonoverFrom').css('display','none')
            $('.showairportnameonoverTo').css('display','none')
        }

        // end script for flight search == == //

        // //  == = == start popular flight Scripts == = = //
        const months = ["+Jan+", "+Feb+", "+Mar+", "+Apr+", "+May+", "+Jun+", "+Jul+", "+Aug+", "+Sep+", "+Oct+", "+Nov+","+Dec+"];

        const d = new Date()
        let year = d.getFullYear();
        let date = d.getDate();
        const currentMonth = new Date();
        let startdate = date + months[currentMonth.getMonth()] + year;

        let newMonth = new Date();
        newMonth.setDate(d.getDate() + 3);
        date = newMonth.getDate();
        let enddate = date + months[newMonth.getMonth()] + year;

        document.getElementById("hydtobang").href = "flight/search?trip-type=oneway&departure=HYD&arrival=BLR&departDate=" + enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("mumtochen").href = "flight/search?trip-type=oneway&departure=BOM&arrival=MAA&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("goatomum").href = "flight/search?trip-type=oneway&departure=GOI&arrival=BOM&departDate=" +enddate + "+&returnDate=" + enddate +"+&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("mumtobang").href = "flight/search?trip-type=oneway&departure=BOM&arrival=BKK&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("mumtodub").href = "flight/search?trip-type=oneway&departure=BOM&arrival=DXB&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("koltomal").href = "flight/search?trip-type=oneway&departure=CCU&arrival=KUL&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltosin").href = "flight/search?trip-type=oneway&departure=DEL&arrival=SIN&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltoahm").href = "flight/search?trip-type=oneway&departure=DEL&arrival=AMD&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("mumtokol").href = "flight/search?trip-type=oneway&departure=BOM&arrival=CCU&departDate=" +enddate + "+&returnDate=" + enddate +"+&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("chentomum").href = "flight/search?trip-type=oneway&departure=MAA&arrival=BOM&departDate=" +enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltojaipur").href = "flight/search?trip-type=oneway&departure=DEL&arrival=JAI&departDate=" + enddate + "+&returnDate=" + enddate +"+&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltoamritsar").href = "flight/search?trip-type=oneway&departure=DEL&arrival=ATQ&departDate=" + enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltoahmedabad").href = "flight/search?trip-type=oneway&departure=DEL&arrival=AMD&departDate=" + enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltochandigarh").href = "flight/search?trip-type=oneway&departure=DEL&arrival=IXC&departDate=" + enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("delhtolucknow").href = "flight/search?trip-type=oneway&departure=DEL&arrival=LKO&departDate=" + enddate + "+&returnDate=" + enddate +"&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";
        document.getElementById("deltokanpur").href = "flight/search?trip-type=oneway&departure=DEL&arrival=PNQ&departDate=" + enddate + "+&returnDate=" + enddate +"+&noOfAdults=1&noOfChilds=0&noOfInfants=0&cabinClass=Y&fare=Regular+Fares";

    </script>

    <Script>
        $(document).ready(function() {
            let selectedVal = 'oneway';
            $('.trip-type').change(function() {
                let selectedVal = $(this).val();
            });

            $('#main-search-btn').on('click', function() {
                $('#main-form').submit();
            });
        });
        $('.carousel').carousel({
            interval: 4000
        })
    </Script>


    <script>
        $('.btn1').click(function() {
            $('.btn1').removeClass('active');
            $(this).addClass('active');
            var activetext = $('.active').text();
            $('#fare4').val(activetext);
        });



        // offer offerPopup script
        function setOfferCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }
        setTimeout(() => {
            setOfferCookie("offerpopup", 'Showed', 1);
        }, 2000)


        // set getOfferCookie
        function getOfferCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1, c.length);
                }
                if (c.indexOf(nameEQ) === 0) {
                    return c.substring(nameEQ.length, c.length);
                }
            }
            return null;
        }
        let offerpopup = getOfferCookie("offerpopup");
        if (offerpopup === null) {
            $(".offerPopup").addClass("active");
            $("body").css("overflow", "hidden")
        } else if (offerpopup == "Showed") {
            $(".offerPopup").removeClass("active");
            $("body").css("overflow", "auto")
        } else {
            $(".offerPopup").removeClass("active");
            $("body").css("overflow", "auto")
        }


        // // close
        setTimeout(() => {
            $("body").css("overflow", "auto")
            $(".offerPopup").removeClass("active");
        }, 15000);


        $(".closeOfferPopup").click(function() {
            $("body").css("overflow", "auto")
            $(".offerPopup").removeClass("active");
        });
    </script>

@endsection
