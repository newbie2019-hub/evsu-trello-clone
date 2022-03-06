<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskAssignee;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // public function index(){
    //     $project = Task::with(['owner', 'owner.info', 'members', 'members.info', 'boards'])->get();
    //     return response()->json($project);
    // }

    public function store(TaskRequest $request){
        $task = Task::create($request->safe()->only(['task', 'project_id', 'board_id']) + [
            'user_id' => auth()->user()->id,
            'order' => $request->validated('order') + 1,
            'status' => 'Pending'
        ]);

        $task->load('assignee', 'assignee.info');
        
        return $this->success('Task created successfully!', $task);
    }

    public function updateTaskAssignee(Request $request){
        $data = [];
        for($i = 0; $i <= count($request->assignee) - 1; $i++){
            $assignee = TaskAssignee::where('task_id', $request->id)
            ->where('user_id', $request->assignee[$i])->first();

            if($assignee){
                $assignee->delete();
            }

            $task = TaskAssignee::create([
                'task_id' => $request->id,
                'user_id' => $request->assignee[$i]
            ]);

            $task->load('info');
            array_push($data, $task);
        } 

        return $this->success('Task has been assigned successfully!', $data);
    }

    public function removeTaskAssignee($id){
        TaskAssignee::destroy($id);
        return $this->success('Assignee has been removed successfully!');
    }

    public function updateOrder(Request $request){
        foreach($request->boards as $board){
            foreach($board['task'] as $task){
                Task::where('id', $task['id'])->update([
                    'board_id' => $task['board_id'],
                    'order' => $task['order'], 
                ]);
            }
        }
    }

    public function update(Request $request, $id){
        $task = Task::where('id', $id)->first();

        if($task){
            $task->update([
                'task' => $request->task,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'delivery_date' => $request->delivery_date,
                'status' => $request->status,
                'type' => $request->type,
            ]);
        }
        else {
            return $this->error('Invalid task data received');
        }

        return $this->success('Task information updated successfully');
    }

    public function destroy($id){
        Task::destroy($id);
        return $this->success('Task has been removed!');
    }
}
