<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $project = Project::whereRelation('members', 'user_id', auth()->user()->id)
        ->orWhere('user_id', auth()->user()->id)->count();
        $task = Task::whereRelation('assignee', 'user_id', auth()->user()->id)
        ->orWhere('user_id', auth()->user()->id)->with(['project', 'user'])->get();
        $issue = Task::where('type', 'Issue')->where('user_id', auth()->user()->id)
        ->orWhereRelation('assignee', 'user_id', auth()->user()->id)
        ->count();

        $logs = ProjectLog::whereRelation('project.members', 'user_id', auth()->user()->id)
        ->orWhereRelation('project', 'user_id', auth()->user()->id)
        ->with(['subject', 'project', 'user.info'])->latest()->take(25)->get();
        
        //Tasks
        // $task = Task::whereRelation('assignee', 'user_id', auth()->user()->id)
        // ->orWhere('user_id', auth()->user()->id)->count();

        return response()->json([
            'projects' => $project,
            'tasks' => $task,
            'issues' => $issue,
            'activity' => $logs
        ]);
    }
}
