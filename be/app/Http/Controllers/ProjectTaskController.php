<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Board;
use App\Models\ProjectLog;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\User;
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

    public function getProjectTasks($id){
        $tasks = Task::where('project_id', $id)->with(['user.info','project'])->get();
        // $tasks = User::whereRelation('tasks', 'project_id', $id)->with(['tasks', 'tasks.project', 'info'])->get();
        return $this->success('Data retrieved successfully!', $tasks);
    }

    public function store(TaskRequest $request){
        $task = Task::create($request->safe()->only(['task', 'project_id', 'board_id']) + [
            'user_id' => auth()->user()->id,
            'order' => $request->validated('order') + 1,
            'status' => 'Pending'
        ]);

        $board = Board::where('id', $request->board_id)->first();
        ProjectLog::create([
            'name' => 'Task Created',
            'event' => 'created',
            'description' => auth()->user()->info->first_name . ' ' .auth()->user()->info->last_name . ' added a new task named ' . $request->task . ' on board named ' . $board->name,
            'subject_type' => Task::class,
            'subject_id' => $task->id,
            'project_id' => $request->project_id,
            'user_id' => auth()->user()->id
        ]);

        $task->load('comments', 'assignee', 'assignee.info');
        
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
        //Update all task from all boards
        foreach($request->boards as $board){
            foreach($board['task'] as $task){
               if($task['moved']){
                   $movedTask = Task::where('id', $task['id'])->first();
                   $previousBoard = Board::where('id', $movedTask->board_id)->first();

                   ProjectLog::create([
                       'name' => 'Task Moved',
                       'event' => 'moved',
                       'description' => auth()->user()->info->first_name . ' ' .auth()->user()->info->last_name . ' moved a task named '. $movedTask->task . ' from board ' . $previousBoard->name . ' to board '. $board['name'],
                       'subject_type' => Task::class,
                       'subject_id' => $movedTask->id,
                       'project_id' => $request->id,
                       'user_id' => auth()->user()->id
                   ]);
               }
               
               Task::where('id', $task['id'])->first()->update([
                    'board_id' => $task['board_id'],
                    'order' => $task['order'], 
               ]);
            }
        }

        return $this->success('Data updated successfully!');

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
