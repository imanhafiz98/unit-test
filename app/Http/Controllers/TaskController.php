<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Auth;

class TaskController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index()
    {
        $tasks = Auth::user()->tasks;
        return view('task.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        $tasks = Task::all();
        return view('task.show', compact('task','tasks'));
    }

    public function create()
    {
        return view('task.create');
    }

    public function store(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'user_id' => 'required',
        ]);

        $task=auth()->user()->tasks()->create($request->all());

        return redirect()->route('user.tasks.index')
                        ->with('success', 'Task created successfully');
    }

    public function edit($id)
    {
        $users = User::all();
        $task = Task::find($id);

        return view('task.edit', compact('task','users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task = $task->update($request->all());

        return redirect()->route('user.tasks.index')
                        ->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('user.tasks.index')
                        ->with('success', 'Task deleted successfully');
    }
}
