<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskCompletedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task,
        public int $userId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // пока просто лог
        Log::info('Task completed notification', [
            'task_id' => $this->task->id,
            'user_id' => $this->userId,
        ]);
    }
}
