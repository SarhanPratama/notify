<?php

namespace App\Listeners;


use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegisteredNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        DB::table('notifications')->insert([
            'title' => 'Registrasi Berhasil',
            'message' => 'User ' . $event->user->name . ' telah berhasil mendaftar.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
