<?php

namespace App\Providers;

use App\Events\EmployeePunchIn;
use App\Events\EmployeePunchOut;
use App\Listeners\MarkAsLate;
use App\Listeners\MarkAsUndertime;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmployeePunchIn::class => [
            MarkAsLate::class
        ],
        EmployeePunchOut::class => [
            MarkAsUndertime::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
