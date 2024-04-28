<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelOfferRequest;
use App\Http\Resources\Admin\HotelOffer\HotelOfferResource;
use App\Models\HotelOffer;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HotelOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hotel_offers = new HotelOffer();
        if (!empty($request->q)) {
            $hotel_offers = $hotel_offers
                ->where('title', 'like', "%$request->q%")
                ->orWhere('content', 'like', "%$request->q%");
        }

        // return Inertia::render('Offer/Index', [
        //     'hotel_offers' => OfferListResource::collection($hotel_offers->paginate(10)->appends($request->all())),
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelOfferRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:hotel_offers,name',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $hotel_offer = HotelOffer::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'rating' => $request->rating,
            'location' => $request->location,
            'price' => $request->price,
            'room_type' => $request->room_type,
        ]);
        if ($hotel_offer) {
            Image::where(['id' => $request->image['id']])->update(['entity_id' => $hotel_offer->id, 'entity_type' => 'offer']);

            return redirect('hotel_offers')->with('flash', createMessage('Hotel offer'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $hotel_offer = HotelOffer::find($id);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => new HotelOfferResource($hotel_offer),
            ]);
        }

        return response()->json(['data' => new HotelOfferResource($hotel_offer), 'success' => true]);
    }
    public function edit($id)
    {
        $offer = HotelOffer::find($id);
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'offer'])->first();
        // return Inertia::render('offer/Form', [
        //     'offer' => new HotelOfferResource($offer),
        //     'image' => $image ?  new ImageResource($image) : null,
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:hotel_offers,name,'.$id,
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $hotel_offer = HotelOffer::find($id);
        if ($hotel_offer) {
            $update = HotelOffer::where(['id' => $hotel_offer->id])->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'rating' => $request->rating,
                'location' => $request->location,
                'price' => $request->price,
                'room_type' => $request->room_type,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $hotel_offer->id, 'entity_type' => 'offer']);
                return redirect('hotel_offers')->with('flash', updateMessage('Hotel offer'));
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Hotel offer not updated',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $offer = HotelOffer::find($id);
        if ($offer->delete()) {
            return response()->json(deleteMessage('Hotel offer'));
        }
        return response()->json(errorMessage());
    }
}
