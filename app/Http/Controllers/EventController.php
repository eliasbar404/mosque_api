<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventMember;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Get All Events
    public function get_all_events(){
        $events = Event::all();
        return $events;
    }

    // Get Event By 
    public function get_event_by_id($id){
        $event = Event::find($id);
        return $event;
    }

    // Create Event
    public function create_event(Request $request){
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:events',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
        ]);

        // Handle the image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            // $imagePath = $request->file('image')->store('events', 'public/images');
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/events'), $imageName);
            $imagePath = 'images/events/' . $imageName;
        }

        $event = Event::create([
            'id'          => \Illuminate\Support\Str::uuid(), // Generate UUID
            'title'       => $validatedData['title'],
            'slug'        => \Illuminate\Support\Str::slug($validatedData['title']),
            'description' => $validatedData['description'],
            'image'       => $imagePath,
            'start_time'  => $validatedData['start_time'],
            'end_time'    => $validatedData['end_time'],
        ]);

        if($event){
            return response()->json("Create new event $event->id Successfully !", 200);
        }

        return response()->json("There is an error !!", 400);
    }

    // Update Event
    public function update_event($id,Request $request){
        // Find event by ID
        $event = Event::find($id);
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after_or_equal:start_time',
        ]);

        // Handle the image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            // Store the new image
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/events'), $imageName);
            $event->image = 'images/events/' . $imageName;
        }

        // Update the event details
        $event->title = $validatedData['title'];
        $event->slug = \Illuminate\Support\Str::slug($validatedData['title']);
        $event->description = $validatedData['description'];
        // $event->status = $validatedData['status'];
        $event->start_time = $validatedData['start_time'];
        $event->end_time = $validatedData['end_time'];
        // $event->published_at = $validatedData['status'] === 'published' ? now() : null;

        // Save the updated event
        $event->save();

        if($event){
            return response()->json("Update The Event $event->id Successfully !", 200);
        }

        return response()->json("There is an error !!", 400);
    }

    // Delete Event
    public function delete_event($id){
        $event = Event::where('id',$id)->first();
        if($event){
            return response()->json("Delete The Event $event->id Successfully !", 200);
        }
        return response()->json("There is an error !!", 400);
    }

    // Publish Event
    public function publish_event($id){
        // Find event by ID
        $event = Event::where('id',$id)->first();
        if($event){
            $event->status = "published";
            $event->published_at = now();
            $event->save();
            return response()->json("Publish The Event $event->id Successfully !", 200);
        }
        return response()->json("There is an error !!", 400);
    }

    // Event view count
    public function event_view_count($id){
        // Find event by ID
        $event = Event::where('id',$id)->first();
        if($event){
            $event->view_count = $event->view_count+1;
            return response()->json("Increase view count of  The Event $event->id Successfully !", 200);
        }
        return response()->json("There is an error !!", 400);

    }


    // Join Event
    public function join_event(Request $request){
        // Validate the incoming request data
        $request->validate([
            'event_id'    => 'required|exists:events,id',
            'member_id'   => 'required|exists:members,id',
            'note'        => 'nullable|string',
        ]);

        $event_member = new EventMember;

        $event_member->id        = \Illuminate\Support\Str::uuid(); // Generate UUID
        $event_member->event_id  = $request->event_id;
        $event_member->member_id = $request->member_id;
        $event_member->note      = $request->note;
        $event_member->save();

        return response()->json("Create Event_member $event_member->id Successfully !", 200);

    }

    // join event status
    public function join_event_status($id,Request $request){
        $request->validate([
            'status'    => 'required',
        ]);

        $event_member = EventMember::where('id',$id)->first();
        if($event_member){
            $event_member->status = $request->status;
            $event_member->save();
            return response()->json("You $request->status member to join the event", 200);
        }

        return response()->json("There is an error !!", 400);

    }



    public function Get_event_joins($id){

        $event = Event::where('id',$id)->first();
        if($event){
            $event_joins = $event->with('members')->get();
            return $event_joins->toArray();
            // return response()->json("Increase view count of  The Event $event->id Successfully !", 200);
        }
        return response()->json("There is an error !!", 400);

    }


}
