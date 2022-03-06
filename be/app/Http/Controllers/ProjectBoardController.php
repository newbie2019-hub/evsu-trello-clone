<?php

namespace App\Http\Controllers;

use App\Models\Board;
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
        }
        else {
            return $this->error('Board data received is invalid');
        }

        return $this->success('Board successfully updated!');
    }
}
