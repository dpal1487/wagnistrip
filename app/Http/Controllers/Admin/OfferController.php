<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offers = new Offer();
        if (!empty($request->q)) {
            $offers = $offers
                ->where('title', 'like', "%$request->q%")
                ->orWhere('content', 'like', "%$request->q%");
        }
        if ($request->status !== null && $request->status !== 'all') {
            $offers = $offers->where('is_published', '=', (int)$request->status);
        }
        // return Inertia::render('Offer/Index', [
        //     'offers' => OfferListResource::collection($offers->paginate(10)->appends($request->all())),
        // ]);
    }

    public function statusUpdate(Request  $request)
    {
        if (Offer::where(['id' => $request->id])->update(['is_published' => $request->status ? 1 : 0])) {
            $status = $request->status == 0  ? "Unpublished" : "Published";
            return response()->json(['message' => "Your Offer has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
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
    public function store(StoreOfferRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:offers,title',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $offer = Offer::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_published' => $request->status,
        ]);
        if ($offer) {
            Image::where(['id' => $request->image['id']])->update(['entity_id' => $offer->id, 'entity_type' => 'offer']);

            return redirect('offers')->with('flash', createMessage('offer'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request , $id)
    {
        $offer = Offer::find($id);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => new OfferResource($offer),
            ]);
        }

        return response()->json(['data' => new OfferResource($offer), 'success' => true]);
    }
    public function edit($id)
    {
        $offer = offer::find($id);
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'offer'])->first();
        // return Inertia::render('offer/Form', [
        //     'offer' => new offerResource($offer),
        //     'image' => $image ?  new ImageResource($image) : null,
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $offer = Offer::find($id);
        if ($offer) {
            $update = Offer::where(['id' => $offer->id])->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'is_published' => $request->status,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $offer->id, 'entity_type' => 'offer']);
                return redirect('offers')->with('flash', updateMessage('offer'));
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'offer not updated',
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $offer = Offer::find($id);
        if ($offer->delete()) {
            return response()->json(deleteMessage('Offer'));
        }
        return response()->json(errorMessage());
    }
}
