<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $tasks = Task::with('user', 'category', 'createdBy')->get();
        } elseif ($user->isManager()) {
            $tasks = Task::where('created_by', $user->id)
                        ->orWhere('user_id', $user->id)
                        ->with('user', 'category', 'createdBy')
                        ->get();
        } else {
            $tasks = $user->tasks()->with('category', 'createdBy')->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $categories = Category::all();
        $user = Auth::user();
        $assignableRoles = $user->getAssignableRoles();
        $assignableUsers = User::whereIn('role', array_map(fn($role) => $role->value, $assignableRoles))->get();

        return view('tasks.create', compact('categories', 'assignableUsers'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $assignedUser = User::find($request->user_id);
        $assignableRoles = $user->getAssignableRoles();
        if (!in_array($assignedUser->role, $assignableRoles)) {
            return response()->json(['error' => 'You can only assign tasks to specific roles.'], 403);
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'created_by' => $user->id,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Task created successfully'], 200);
    }

    public function show(Task $task)
    {
        $this->authorizeTask($task);
        $task->load('comments.user', 'createdBy');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task, true);
        $categories = Category::all();
        $user = Auth::user();
        $assignableRoles = $user->getAssignableRoles();
        $assignableUsers = User::whereIn('role', array_map(fn($role) => $role->value, $assignableRoles))->get();

        return view('tasks.edit', compact('task', 'categories', 'assignableUsers'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task, true);
        $user = Auth::user();

        $request->validate([
            'title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $assignedUser = User::find($request->user_id);
        $assignableRoles = $user->getAssignableRoles();
        if (!in_array($assignedUser->role, $assignableRoles)) {
            return response()->json(['error' => 'You can only assign tasks to specific roles.'], 403);
        }

        $task->update($request->all());
        return response()->json(['message' => 'Task updated successfully'], 200);
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task, true);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }

    private function authorizeTask(Task $task, $creatorOnly = false)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }
        if ($user->isManager()) {
            if ($task->created_by === $user->id || $task->user_id === $user->id) {
                return;
            }
        }
        if ($user->isUser() && !$creatorOnly) {
            if ($task->user_id === $user->id) {
                return;
            }
        }
        abort(403, 'Unauthorized');
    }
}
