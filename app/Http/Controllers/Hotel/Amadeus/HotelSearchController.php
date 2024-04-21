<?php

namespace App\Http\Controllers\Hotel\Amadeus;

use App\Http\Controllers\Controller;
use App\Services\AmadeusService;

class HotelSearchController extends Controller
{
    protected $amadeus;

    public function __construct(AmadeusService $amadeus)
    {
        $this->amadeus = $amadeus;
    }

    public function hotelSearch()
    {
        $searchParams = [
            'cityCode' => 'DEL', // Replace with desired city code
            'checkInDate' => date('Y-m-d', strtotime('+1 day')), // Check-in date (tomorrow)
            'checkOutDate' => date('Y-m-d', strtotime('+3 day')), // Check-out date (3 days from now)
            'pageSize' => 10, // Number of results to return
        ];

        try {
            $amadeus = $this->amadeus->getClient();

            $hotels = $amadeus->getHotelOffers()->search($searchParams);

            // Process hotel results (e.g., display in a view or return as JSON)
        } catch (\Exception $e) {
            // Handle API errors (e.g., log the error or return a user-friendly message)
        }
    }
}
