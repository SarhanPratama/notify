<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Listeners\SendUserRegisteredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Event listener aplikasi.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendUserRegisteredNotification::class,
        ],
    ];

    /**
     * Daftarkan event aplikasi.
     */
    public function boot()
    {
        parent::boot();
    }
}
