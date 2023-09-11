<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function get($organizerID, $eventID) {
        $event = Event::where('id', $eventID)
        ->with(['tickets', 'sessions'])
        ->first();

        return response()->json([
            'status' => 200,
            'event' => $event,
        ]);
    }
    public function detail($eventID) {
        $event = Event::where('id', $eventID)
        ->with(['tickets', 'sessions', 'organizer'])
        ->first();

        return response()->json([
            'status' => 200,
            'event' => $event,
        ]);
    }
    public function search(Request $request) {
        $filter = [];
        $refreshData = false;

        if ($request->q != "") {
            array_push($filter, ['title', 'LIKE', '%'.$request->q.'%']);
        }
        if ($request->city != "" && strtolower($request->city) != "semua") {
            array_push($filter, ['city', 'LIKE', '%'.$request->city.'%']);
        }
        if ($request->topic != "" && strtolower($request->topic) != "semua") {
            array_push($filter, ['topic', 'LIKE', '%'.$request->topic.'%']);
        }

        if (count($filter) > 0) {
            $refreshData = true;
        }
        
        $events = Event::where($filter)
        ->orderBy('created_at', 'DESC')
        ->with(['tickets', 'organizer'])
        ->paginate(15);

        return response()->json([
            'status' => 200,
            'events' => $events,
            'refresh_data' => $refreshData
        ]);
    }
    public function create($organizerID, Request $request) {
        $tickets = json_decode($request->tickets, false);
        $cover = $request->file('cover');
        $coverFileName = $organizerID."_".$cover->getClientOriginalName();

        $saveData = Event::create([
            'organizer_id' => $organizerID,
            'title' => $request->title,
            'description' => $request->description,
            'cover' => $coverFileName,
            'address' => $request->address,
            'city' => $request->city,
            'start_date' => $request->start,
            'end_date' => $request->end,
            'max_buy_ticket' => $request->max_buy_ticket,
            'province' => "default",
        ]);

        $createSession = EventSession::create([
            'event_id' => $saveData->id,
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ]);
        
        foreach ($tickets as $ticket) {
            $createTicket = Ticket::create([
                'event_id' => $saveData->id,
                'name' => $ticket->name,
                'description' => $ticket->description,
                'quantity' => $ticket->quantity,
                'start_quantity' => $ticket->quantity,
                'price' => $ticket->price,
                'start_sale' => Carbon::now()->format('Y-m-d H:i:s'),
                'end_sale' => Carbon::parse($request->end)->format('Y-m-d H:i:s'),
                'type' => "whole"
            ]);
        }

        $cover->storeAs('public/event_covers', $coverFileName);

        return response()->json([
            'status' => 200,
            'message' => "Berhasil membuat event"
        ]);
    }
    public function delete($organizerID, $eventID) {
        $data = Event::where('id', $eventID);
        $event = $data->first();

        $deleteData = $data->delete();
        $deleteCover = Storage::delete('public/event_covers/' . $event->cover);
        
        return response()->json([
            'status' => 200,
            'message' => "Berhasil menghapus event"
        ]);
    }
    public function featured(Request $request) {
        $token = $request->token;

        if ($token == "") {
            $query = Event::orderBy('created_at', 'DESC');
        } else {
            $query = Event::orderBy('created_at', 'DESC');
        }

        $events = $query->with(['organizer', 'tickets'])->take(10)->get();

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
}
