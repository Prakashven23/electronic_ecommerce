<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\OrderPlaced;
use App\Listeners\SendWhatsappConfirmation;
use App\Events\OrderPaid;
use App\Listeners\SendWhatsAppThankYou;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPlaced::class => [
            SendWhatsappConfirmation::class,
        ],
        OrderPaid::class => [
            SendWhatsAppThankYou::class,
        ],
    ];
} 