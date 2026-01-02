<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class EnforceSingleSession
{
    public function handle(Login $event)
    {
        $user = $event->user;

        if ($user->allow_multi_session) {
    return;
}



        // Supprimer toutes les autres sessions
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', session()->getId())
            ->delete();

        // ✅ TRÈS IMPORTANT : sauvegarder la session ACTIVE
        $user->session_id = session()->getId();
        $user->save();
    }
}
