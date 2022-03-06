<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Backlog',
                'description' => 'Back logs goes here. Short description for this card goes here.',
                'color' => 'red lighten-1 white--text',
                'order' => 1,
                'project_id' => 1
            ],
            [
                'name' => 'To-do',
                'description' => 'To-do task goes here. Short description for this card goes here.',
                'color' => 'primary lighten-1 white--text',
                'order' => 2,
                'project_id' => 1
            ],
            [
                'name' => 'In-progress',
                'description' => 'In-progress tasks goes here. Short description for this card goes here.',
                'color' => 'grey darken-2 white--text',
                'order' => 3,
                'project_id' => 1
            ],
            [
                'name' => 'Finished',
                'description' => 'Finished tasks goes here. Short description for this card goes here.',
                'color' => 'green lighten-1 white--text',
                'order' => 4,
                'project_id' => 1
            ],
            [
                'name' => 'Backlog',
                'description' => 'Back logs goes here. Short description for this card goes here.',
                'color' => 'red lighten-1 white--text',
                'order' => 1,
                'project_id' => 2
            ],
            [
                'name' => 'To-do',
                'description' => 'To-do task goes here. Short description for this card goes here.',
                'color' => 'primary lighten-1 white--text',
                'order' => 2,
                'project_id' => 2
            ],
            [
                'name' => 'In-progress',
                'description' => 'In-progress tasks goes here. Short description for this card goes here.',
                'color' => 'grey darken-2 white--text',
                'order' => 3,
                'project_id' => 2
            ],
            [
                'name' => 'Finished',
                'description' => 'Finished tasks goes here. Short description for this card goes here.',
                'color' => 'green lighten-1 white--text',
                'order' => 4,
                'project_id' => 2
            ],
            [
                'name' => 'Backlog',
                'description' => 'Back logs goes here. Short description for this card goes here.',
                'color' => 'red lighten-1 white--text',
                'order' => 1,
                'project_id' => 3
            ],
            [
                'name' => 'To-do',
                'description' => 'To-do task goes here. Short description for this card goes here.',
                'color' => 'primary lighten-1 white--text',
                'order' => 2,
                'project_id' => 3
            ],
            [
                'name' => 'In-progress',
                'description' => 'In-progress tasks goes here. Short description for this card goes here.',
                'color' => 'grey darken-2 white--text',
                'order' => 3,
                'project_id' => 3
            ],
            [
                'name' => 'Finished',
                'description' => 'Finished tasks goes here. Short description for this card goes here.',
                'color' => 'green lighten-1 white--text',
                'order' => 4,
                'project_id' => 3
            ],
        ];

        foreach($data as $board){
            Board::create($board);
        }
    }
}
