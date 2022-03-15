<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\TaskAssignee;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $project = Project::whereRelation('members', 'user_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id)->with([
            'owner', 'owner.info', 'members', 'members.info', 
            'boards', 'boards.task', 'boards.task.user', 'boards.task.user.info',
            'boards.task.assignee', 'boards.task.assignee.info',
            'boards.task.comments', 'boards.task.comments.user.info'
            ])->get();
        return response()->json($project);
    }

    public function store(ProjectRequest $request){
        Project::create($request->validated() + [
            'user_id' => auth()->user()->id,
            'slug' => $request->validated('name')
        ]);
        return $this->success('Project created successfully!');
    }

    public function update(Request $request, $id){
        $project = Project::where('id', $id)->first();
        $project->update([
            'name' => $request->name,
            'slug' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'delivery_date' => $request->delivery_date,
        ]);

        return $this->success('Project updated successfully');
    }

    public function destroy($id){
        Project::destroy($id);
        return $this->success('Project has been deleted successfully!');
    }

    public function removeProjectMember($projectid, $id){
        $proj = ProjectMember::where('user_id', $id)->where('project_id', $projectid)->first();
        $task = Task::where('project_id', $proj->project_id)->whereRelation('assignee', 'user_id', $proj->user_id)->get();
        if($task){
            foreach($task as $assignee){
              TaskAssignee::where('user_id', $id)->where('task_id', $assignee->id)->delete();
            }
        }
        // $task->assignee->delete();
        ProjectMember::where('user_id', $id)->delete();
        return $this->success('Project member has been removed successfully!', $task);
    }
}
