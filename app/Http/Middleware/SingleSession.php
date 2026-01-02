<?php
namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SingleSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
    $user = Auth::user();

    // ✅ EXCEPTION
    if ($user->allow_multi_session) {
        return $next($request);
    }

    $currentSessionId = session()->getId();
    $userSessionId = $user->session_id;

    if ($userSessionId && $userSessionId !== $currentSessionId) {

        DB::table('sessions')->where('id', $userSessionId)->delete();

        $user->session_id = $currentSessionId;
        $user->save();

        Session::flash(
            'session_error',
            'Votre compte a été ouvert sur un autre appareil.'
        );

        Auth::logout();

        return redirect()->route('login.form');
    }
}


        return $next($request);
    }
}
