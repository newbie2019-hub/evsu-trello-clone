<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectTaskSeeder extends Seeder
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
                'task' => 'Backlog - Task Sample',
                'description' => 'Task description can be added here. Please be more descriptive when writing task description so it will be clear for the assignee.',
                'type' => 'New Feature',
                'status' => 'Pending',
                'start_date' => '2022-03-25',
                'delivery_date' => '2022-04-25',
                'order' => 1,
                'user_id' => 1,
                'project_id' => 1,
                'board_id' => 1,
            ],
            [
                'task' => 'In-Progress - Task Sample',
                'description' => 'Task description can be added here. Please be more descriptive when writing task description so it will be clear for the assignee.',
                'type' => 'New Feature',
                'status' => 'In-Progress',
                'start_date' => '2022-03-20',
                'delivery_date' => '2022-03-25',
                'order' => 1,
                'user_id' => 1,
                'project_id' => 1,
                'board_id' => 1,
            ],
            [
                'task' => 'Another Task Sample',
                'description' => 'Task description can be added here. Please be more descriptive when writing task description so it will be clear for the assignee.',
                'type' => 'New Feature',
                'status' => 'Pending',
                'start_date' => '2022-03-25',
                'delivery_date' => '2022-03-30',
                'order' => 1,
                'user_id' => 1,
                'project_id' => 1,
                'board_id' => 1,
            ],
        ];

        foreach($data as $task){
            Task::create($task);
        }
    }
}
