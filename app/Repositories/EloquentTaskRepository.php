<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Task::query()
            ->when($filters['user_id'] ?? null, fn ($q, $userId) => $q->where('user_id', $userId)
            )
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status)
            )
            ->paginate(10);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
