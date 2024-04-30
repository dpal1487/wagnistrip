<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Testimonial\StoreTestimonialRequest;

use App\Http\Requests\Testimonial\UpdateTestimonialRequest;use App\Models\Testimonial;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages/testimonial');
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
    public function store(StoreTestimonialRequest $request)
    {
        $testimonial = Testimonial::create([
            'author' => $request->author,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);
        return response()->json([
            'success' => true,
            'data' => $testimonial], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update(['author' => $request->author,
            'content' => $request->content,
            'rating' => $request->rating]);
        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'], 200);
    }
}
