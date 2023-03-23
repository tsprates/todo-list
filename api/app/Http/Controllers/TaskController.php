<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), (new StoreTaskRequest())->rules());
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $newTask = Task::create($request->all());

        return response()->json($newTask, 201);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), (new UpdateTaskRequest())->rules());
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $task->update($request->all());

        return response()->json($task, 200);
    }

    public function destroy(Task $task)
    {    
        if (!$task->delete()) {
            return response()->json(['errors' => 'an issue occured when deleting the item'], 400);
        }
  
        return response()->json($task, 200);
    }
}
