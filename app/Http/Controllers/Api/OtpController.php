<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function auth(Request $request) {
        $code = $request->code;
        $token = $request->token;
        // $code = 6050;
        // $token = "tUySqHPP3xksJZLHNEKRxJHAobSnZDxp";
        $user = User::where('token', $token)->first();
        $res = [
            'status' => 402,
            'message' => "Kode OTP Salah"
        ];

        $o = Otp::where([
            ['code', $code],
            ['has_used', false]
        ]);
        $otp = $o->first();

        if ($otp != "" && $user->id == $otp->user_id) {
            $res['status'] = 200;
            $res['message'] = "Berhasil login";
            $o->update([
                'has_used' => true,
            ]);
        }

        return response()->json($res);
    }
}
