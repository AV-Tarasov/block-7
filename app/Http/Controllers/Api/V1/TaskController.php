<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TaskCompleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TaskRepositoryInterface $repo
    ) {}

    public function index(Request $request)
    {
        return TaskResource::collection(
            $this->repo->getAll([
                ...$request->all(),
                'user_id' => $request->user()->id,
            ])
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->repo->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $oldStatus = $task->status;

        $task = $this->repo->update($task, $request->validated());

        if ($oldStatus !== 'done' && $task->status === 'done') {
            event(new TaskCompleted($task, [
                'user_id' => $request->user()->id,
                'from' => $oldStatus,
            ]));
        }

        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $this->repo->delete($task);

        return response()->noContent();
    }
}
