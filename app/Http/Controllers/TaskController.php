<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Fetch all tasks
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    // Create a new task
    public function store(Request $request)
    {

        //validtion
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    // Fetch a single task
    public function show($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json($task, 200);
    }

    // Update a task
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        //validtion
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $task->update($request->all());
        return response()->json($task, 200);
    }

    // Delete a task
    public function destroy($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
