<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
  
    public function handle(Request $request, Closure $next): Response
    {

    if(Auth::user()->status !== 'مفعل'){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('error', 'الحساب غير مفعل. تواصل مع الإدارة');

    }else{

              return $next($request);
   
    }
}


}