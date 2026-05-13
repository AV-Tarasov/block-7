<?php

namespace App\Providers;

use App\Events\TaskCompleted;
use App\Listeners\WriteTaskAuditLog;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskCompleted::class => [
            WriteTaskAuditLog::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
