<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $myData = Auth::guard('admin')->user();
        if ($myData == "") {
            $r = base64_encode(URL::full());
            return redirect()->route('admin.loginPage', ['r' => $r])->withErrors([
                'Login dulu sebelum melanjutkan'
            ]);
        }
        return $next($request);
    }
}
