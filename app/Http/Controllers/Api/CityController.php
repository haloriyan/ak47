<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Event;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function get() {
        $cities = City::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();
        foreach ($cities as $city) {
            $city->event_count = Event::where('city', 'LIKE', '%'.$city->name.'%')->get('id')->count();
        }
        
        return response()->json([
            'status' => 200,
            'cities' => $cities,
        ]);
    }
    public function event($name, Request $request) {
        $name = base64_decode($name);
        $city = City::where('name', 'LIKE', '%'.$name.'%')->first();
        $events = Event::where('city', 'LIKE', '%'.$name.'%')->with(['tickets'])->get();

        return response()->json([
            'status' => 200,
            'events' => $events,
            'city' => $city
        ]);
    }
}
