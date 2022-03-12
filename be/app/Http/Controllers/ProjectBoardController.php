<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\ProjectLog;
use Illuminate\Http\Request;

class ProjectBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $board = Board::create([
            'name' => $request->name,
            'color' => 'grey darken-3 white--text',
            'order' => $request->board_count + 1,
            'project_id' => $request->project_id
        ]);

        ProjectLog::create([
            'name' => 'Board Created',
            'event' => 'created',
            'description' => 'A new board named ' . $request->name . ' has been created ',
            'subject_type' => Board::class,
            'subject_id' => $board->id,
            'project_id' => $request->project_id,
            'user_id' => auth()->user()->id
        ]);

        $board->load('task');

        return $this->success('Board added successfully!', $board);
    }

    public function updateOrder(Request $request){
        foreach($request->boards as $board){
            Board::where('id', $board['id'])->update([
                'order' => $board['order'], 
            ]);
        }
    }

    public function updateName(Request $request){
        $board = Board::where('id', $request->board_id)->first();

        if($board){
            $board->update([
                'name' => $request->name
            ]);

            ProjectLog::create([
                'name' => 'Board Rename',
                'event' => 'rename',
                'description' => auth()->user()->info->first_name . ' ' .auth()->user()->info->last_name . ' renamed a board from '. $board->name . ' to ' . $request->name,
                'subject_type' => Board::class,
                'subject_id' => $board->id,
                'project_id' => $board->project_id,
                'user_id' => auth()->user()->id
            ]);
     
        }
        else {
            return $this->error('Board data received is invalid');
        }

        return $this->success('Board successfully updated!');
    }
}
