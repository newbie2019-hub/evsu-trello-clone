<?php

namespace App\Http\Controllers;

use App\Models\ProjectLog;
use Illuminate\Http\Request;

class ProjectLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $logs = ProjectLog::whereRelation('project.members', 'user_id', auth()->user()->id)
        ->orWhereRelation('project', 'user_id', auth()->user()->id)
        ->with(['subject', 'project', 'user.info'])->get();

        return $this->success('Project Logs has been retrieved successfully!', $logs);
    }

    public function getProjectActivity($id){
        $projAct = ProjectLog::where('project_id', $id)->with(['user.info'])->latest()->take(10)->get();
        return $this->success('Project Logs has been retrieved successfully!', $projAct);
    }
}
