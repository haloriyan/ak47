<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public static function get($token) {
        return User::where('token', $token);
    }
    public static function isEmail($data) {
        $d = explode("@", $data);
        if (@$d[1] != "") {
            return true;
        }
        return false;
    }
    public function login(Request $request) {
        // $email = $request->email;
        $email = "riyan.satria.619@gmail.com";
        $u = User::where('email', $email);
        $user = $u->first();
        $otp = null;
        $token = Str::random(32);

        if ($user == "") {
            // register
            $name = explode("@", $email)[0];
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt("inikatasandi"),
                'token' => $token
            ]);
        }

        $code = rand(1111, 9999);
        $otp = Otp::create([
            'user_id' => $user->id,
            'code' => $code,
            'action' => "login",
            'has_used' => false
        ]);
        
        $u->update([
            'token' => $token,
        ]);
        $user = $u->first();

        return response()->json([
            'status' => 200,
            'message' => "Berhasil login",
            'user' => $user,
            'otp' => $otp,
        ]);
    }
    public function logout(Request $request) {
        $loggingOut = self::get($request->token)->update([
            'token' => null
        ]);

        return response()->json([
            'status' => 200,
            'message' => "Berhasil logout"
        ]);
    }
    public function profile(Request $request) {
        $user = User::where('token', $request->token)->first();
        $status = 200;

        if ($user == "") {
            $status = 405;
        }
        return response()->json([
            'status' => $status,
            'user' => $user,
        ]);
    }

    public function auth(Request $request) {
        $query = User::where('token', $request->token);
        $user = $query->with(['premium', 'package'])->first();
        
        $res = ['status' => 200];
        if ($user == "") {
            $res['status'] = 401;
        }

        return response()->json($res);
    }
}
