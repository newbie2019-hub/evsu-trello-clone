<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\ProjectLog;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $taskcomment = TaskComment::create([
            'task_id' => $request->id,
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
        ]);

        $task = Task::where('id', $taskcomment->task_id)->first();
        ProjectLog::create([
            'name' => 'Task Comment',
            'event' => 'comment',
            'description' => auth()->user()->info->first_name . ' ' .auth()->user()->info->last_name . ' commented on task ' . $task->task ,
            'subject_type' => TaskComment::class,
            'subject_id' => $taskcomment->id,
            'project_id' => $task->project_id,
            'user_id' => auth()->user()->id
        ]);

        $taskcomment->load(['user', 'user.info']);
        return $this->success('Comment has been saved successfully!', $taskcomment);
    }
}
