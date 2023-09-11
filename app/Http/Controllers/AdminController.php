<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Organizer;
use App\Models\Package;
use App\Models\Topic;
use App\Models\User;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public static function me() {
        $myData = Auth::guard('admin')->user();
        if ($myData != "") {
            // 
        }
        return $myData;
    }
    public function loginPage(Request $request) {
        $message = Session::get('message');

        return view('admin.login', [
            'message' => $message,
            'request' => $request,
        ]);
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            $params = [];
            if ($request->r != "") {
                $params['r'] = $request->r;
            }
            return redirect()->route('admin.loginPage', $params)->withErrors([
                'Kombinasi email dan password tidak tepat'
            ]);
        }

        if ($request->r != "") {
            $r = base64_decode($request->r);
            return redirect($r);
        }

        return redirect()->route('admin.dashboard');
    }
    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();
        
        return redirect()->route('admin.loginPage')->with([
            'message' => "Berhasil logout"
        ]);
    }

    public function dashboard() {
        $myData = self::me();
        
        return view('admin.dashboard', [
            'myData' => $myData,
        ]);
    }
    public function event() {
        $myData = self::me();
        
        return view('admin.event', [
            'myData' => $myData,
        ]);
    }
    public function user(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $query = User::orderBy('created_at', 'DESC');

        if ($request->q != "") {
            $query = $query->where('name', 'LIKE', '%'.$request->q.'%');
        }
        $users = $query->paginate(25);
        
        return view('admin.user', [
            'myData' => $myData,
            'message' => $message,
            'request' => $request,
            'users' => $users,
        ]);
    }
    public function organizer(Request $request) {
        $myData = self::me();
        $message = Session::get('message');

        $query = Organizer::orderBy('created_at', 'DESC');
        if ($request->q != "") {
            $query = $query->where('name', 'LIKE', '%'.$request->q.'%');
        }
        $organizers = $query->paginate(25);
        
        return view('admin.organizer', [
            'myData' => $myData,
            'organizers' => $organizers,
            'request' => $request,
            'message' => $message,
        ]);
    }
    public function package(Request $request) {
        $myData = self::me();
        $message = Session::get('message');

        $packages = Package::orderBy('price_monthly', 'ASC')->get();

        return view('admin.package', [
            'myData' => $myData,
            'message' => $message,
            'packages' => $packages,
        ]);
    }
    public function category(Request $request) {
        $myData = self::me();
        $message = Session::get('message');
        $categories = Category::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();

        return view('admin.category', [
            'myData' => $myData,
            'message' => $message,
            'categories' => $categories,
        ]);
    }
    public function city() {
        $myData = self::me();
        $message = Session::get('message');
        $cities = City::orderBy('priority', 'DESC')->orderBy('created_at', 'DESC')->get();

        return view('admin.city', [
            'myData' => $myData,
            'message' => $message,
            'cities' => $cities,
        ]);
    }
    public function topic() {
        $myData = self::me();
        $message = Session::get('message');
        $topics = Topic::orderBy('priority', 'DESC')->orderBy('updated_at', 'DESC')->get();

        return view('admin.topic', [
            'myData' => $myData,
            'message' => $message,
            'topics' => $topics,
        ]);
    }
}
