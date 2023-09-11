<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function get(Request $request) {
        $user = User::where('token', $request->token)->first();
    }
}
