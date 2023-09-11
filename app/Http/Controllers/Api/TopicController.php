<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function get() {
        $topics = Topic::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();
        
        return response()->json([
            'status' => 200,
            'topics' => $topics
        ]);
    }
}
