<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\OrganizerMembership;
use App\Models\OrganizerTeam;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Xendit\Xendit as Xendit;

class OrganizerController extends Controller
{
    public function get(Request $request) {
        $token = $request->token;
        Log::info($token);

        $user = User::where('token', $token)->first();
        $organizers = [];

        $teams = OrganizerTeam::where('user_id', $user->id)->with('organizer')->get();
        foreach ($teams as $team) {
            array_push($organizers, $team->organizer);
        }

        return response()->json([
            'status' => 200,
            'organizers' => $organizers,
        ]);
    }
    public function profile($id, Request $request) {
        $token = $request->token;
        $user = User::where('token', $token)->first();
        $organizer = Organizer::where('id', $id)->with(['teams', 'membership.package'])->first();

        $isInTeam = false;
        foreach ($organizer->teams as $team) {
            if ($team->user_id == $user->id) {
                $isInTeam = true;
            }
        }

        if (!$isInTeam) {
            $organizer = null;
        }

        return response()->json([
            'status' => 200,
            'organizer' => $organizer
        ]);
    }
    public function upgrade($id, Request $request) {
        $organizer = Organizer::where('id', $id)->first();
        $package = Package::where('id', $request->package_id)->first();
        $period = $request->period;

        $secretKey = env('XENDIT_MODE') == 'sandbox' ? env('XENDIT_SECRET_KEY_SANDBOX') : env('XENDIT_SECRET_KEY');
        Xendit::setApiKey($secretKey);
        $externalID = "AKPL_".$id.time();
        $amount = $package->{"price_".$period};
        $createInvoice = \Xendit\Invoice::create([
            'external_id' => $externalID,
            'amount' => $amount,
            'description' => "Payment for " . $organizer->name . "'s account upgrade",
            'payer_email' => $organizer->contact_email,
        ]);

        $now = Carbon::now();
        $expiration = $period === "monthly" ? $now->addMonth() : $now->addYear();

        $createPurchase = OrganizerMembership::create([
            'organizer_id' => $organizer->id,
            'package_id' => $package->id,
            'expiration' => $expiration->format('Y-m-d H:i:s'),
            'payment_reference' => $externalID,
            'payment_status' => "PENDING",
            'payment_amount' => $amount,
            'payment_url' => $createInvoice['invoice_url']
        ]);

        return response()->json([
            'status' => 200,
            'invoice' => $createInvoice,
        ]);
    }
    public function create(Request $request) {
        $user = User::where('token', $request->token)->first();
        $username = Str::random(32);

        $icon = $request->file('icon');
        $iconFileName = $icon->getClientOriginalName();
        $cover = $request->file('cover');
        $coverFileName = $cover->getClientOriginalName();

        $saveData = Organizer::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $iconFileName,
            'cover' => $coverFileName,
            'contact_email' => $user->email,
            'username' => $username,
            'type' => "Event Organizer"
        ]);

        $saveTeam = OrganizerTeam::create([
            'organizer_id' => $saveData->id,
            'user_id' => $user->id,
            'role' => "Administrator"
        ]);

        $cover->storeAs('public/organizer_covers/', $coverFileName);
        $icon->storeAs('public/organizer_icons', $iconFileName);

        return response()->json([
            'status' => 200,
            'message' => "Berhasil membuat organizer"
        ]);
    }

    public function updateProfile($id, Request $request) {
        $data = Organizer::where('id', $id);
        $organizer = $data->first();

        $toUpdate = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconFileName = $icon->getClientOriginalName();
            $deleteOldIcon = Storage::delete('public/organizer_icons/' . $organizer->icon);
            $toUpdate['icon'] = $iconFileName;
            $icon->storeAs('public/organizer_icons', $iconFileName);
        }
        if ($request->hasFile('cover')) {
            $cover = $request->file('cover');
            $coverFileName = $cover->getClientOriginalName();
            $deleteOldCover = Storage::delete('public/organizer_covers/' . $organizer->cover);
            $toUpdate['cover'] = $coverFileName;
            $cover->storeAs('public/organizer_covers', $coverFileName);
        }

        $updateData = $data->update($toUpdate);

        return response()->json([
            'status' => 200,
            'message' => "Berhasil mengubah data organizer " . $organizer->name,
        ]);
    }
    public function event($id) {
        $events = Event::where('organizer_id', $id)
        ->with(['tickets', 'sessions'])
        ->orderBy('created_at', 'DESC')
        ->get();

        return response()->json([
            'status' => 200,
            'events' => $events
        ]);
    }
}
