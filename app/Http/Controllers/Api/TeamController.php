<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganizerTeam;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function get($organizerID, Request $request) {
        $token = $request->token;
        $isInTeam = false;
        $user = User::where('token', $token)->first();
        
        $teams = OrganizerTeam::where('organizer_id', $organizerID)
        ->with('user')
        ->get();

        foreach ($teams as $team) {
            if ($team->user_id == $user->id) {
                $isInTeam = true;
            }
        }

        if (!$isInTeam) {
            $teams = null;
        }

        return response()->json([
            'status' => 200,
            'teams' => $teams
        ]);
    }
}
