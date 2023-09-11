<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get() {
        $categories = Category::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();

        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }
    public function getEvent($name) {
        // $category = Category::where('id', $id)->first();
        $events = Event::where('category', 'LIKE', '%'.$name.'%')
        ->with(['tickets'])->paginate(25);

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
}
