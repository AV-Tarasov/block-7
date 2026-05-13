<?php

namespace App\Listeners;

use App\Jobs\SendTaskCompletedNotification;
use App\Models\TaskAudit;

class WriteTaskAuditLog
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
    public function handle(object $event): void
    {
        TaskAudit::create([
            'task_id' => $event->task->id,
            'event' => class_basename($event),
            'occurred_at' => now(),
            'meta' => $event->meta,
        ]);

        SendTaskCompletedNotification::dispatch(
            $event->task,
            $event->meta['user_id'] ?? $event->task->user_id
        );
    }
}
