<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;use App\Http\Resources\Admin\EventResource;

use App\Models\Event;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = new Event();
        if (!empty($request->q)) {
            $events = $events
                ->where('title', 'like', "%$request->q%")
                ->orWhere('content', 'like', "%$request->q%");
        }
        if ($request->status !== null && $request->status !== 'all') {
            $events = $events->where('is_published', '=', (int) $request->status);
        }
        // return Inertia::render('Event/Index', [
        //     'events' => EventListResource::collection($events->paginate(10)->appends($request->all())),
        // ]);
    }

    public function statusUpdate(Request $request)
    {
        if (Event::where(['id' => $request->id])->update(['is_published' => $request->status ? 1 : 0])) {
            $status = $request->status == 0 ? "Unpublished" : "Published";
            return response()->json(['message' => "Your Event has been " . $status, 'success' => true]);
        }
        return response()->json(['message' => 'Opps! something went wrong.', 'success' => false]);
    }
    public function create()
    {
        // return Inertia::render('Event/Form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $event = Event::create([
            'name' => $request->title,
            'phone' => $request->title,
            'email' => $request->email,
            'eventdate' => $request->eventdate,
            'eventtype' => $request->eventtype,
            'eventcity' => $request->eventcity,
        ]);
        if ($event) {
            Image::where(['id' => $request->image['id']])->update(['entity_id' => $event->id, 'entity_type' => 'Event']);

            return redirect('events')->with('flash', createMessage('Event'));
        }
        return redirect('Event');
    }

    public function show(Request $request, $id)
    {
        $event = Event::find($id);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => new EventResource($event),
            ]);
        }

        // return Inertia::render('Event/Show', [
        //     'Event' => new EventResource($Event),
        // ]);
        return response()->json(['data' => new EventResource($event), 'success' => true]);
    }
    public function edit($id)
    {
        $event = Event::find($id);
        $image = Image::where(['entity_id' => $id, 'entity_type' => 'Event'])->first();
        // return Inertia::render('Event/Form', [
        //     'Event' => new EventResource($Event),
        //     'image' => $image ?  new ImageResource($image) : null,
        // ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['message' => $validator->errors()->first(), 'success' => false]);
        }
        $event = Event::find($id);
        if ($event) {
            $update = Event::where(['id' => $event->id])->update([
                'name' => $request->title,
                'phone' => $request->title,
                'email' => $request->email,
                'eventdate' => $request->eventdate,
                'eventtype' => $request->eventtype,
                'eventcity' => $request->eventcity,
            ]);
            if ($update) {
                Image::where('id', $request->image['id'])->update(['entity_id' => $event->id, 'entity_type' => 'Event']);
                return redirect('events')->with('flash', updateMessage('Event'));
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Event not updated',
        ], 400);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event->delete()) {
            return response()->json(deleteMessage('Event'));
        }
        return response()->json(errorMessage());
    }
}
