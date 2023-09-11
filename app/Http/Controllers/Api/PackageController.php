<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganizerMembership;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function get() {
        $packages = Package::orderBy('price_monthly', 'ASC')->get();
        return response()->json([
            'status' => 200,
            'packages' => $packages
        ]);
    }
    public function invoiceCallback(Request $request) {
        $externalID = $request->external_id;
        $data = OrganizerMembership::where('payment_reference', $externalID);
        $membership = $data->first();

        $data->update([
            'payment_status' => $request->status,
        ]);

        return response()->json([
            'message' => "ok"
        ]);
    }
}
