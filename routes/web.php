<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Auth::routes();

Route::resource('tasks', TaskController::class);
Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');