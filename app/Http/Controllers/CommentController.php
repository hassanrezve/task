<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
{
    $request->validate(['body' => 'required']);

    $comment = $task->comments()->create([
        'user_id' => Auth::id(),
        'body' => $request->body,
    ]);

    return response()->json([
        'message' => 'Comment added successfully',
        'comment' => [
            'user' => ['name' => Auth::user()->name],
            'body' => $comment->body,
            'created_at' => now()->diffForHumans(),
        ]
    ], 200);
}

}