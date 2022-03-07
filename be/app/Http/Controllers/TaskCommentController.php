<?php

namespace App\Http\Controllers;

use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $task = TaskComment::create([
            'task_id' => $request->id,
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
        ]);

        $task->load(['user', 'user.info']);
        return $this->success('Comment has been saved successfully!', $task);
    }
}
