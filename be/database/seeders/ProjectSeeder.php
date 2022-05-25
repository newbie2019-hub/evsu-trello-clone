<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
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
                'name' => 'Trello x Jira clone',
                'slug' => 'trello-x-jira-clone',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'start_date' => '2022-03-15',
                'delivery_date' => '2022-05-15',
                'user_id' => 1
            ],
            [
                'name' => 'School Management System',
                'slug' => 'school-management-system',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'start_date' => '2022-03-20',
                'delivery_date' => '2022-08-15',
                'user_id' => 1
            ],
            [
                'name' => 'Office Management System',
                'slug' => 'office-management-system',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'start_date' => '2022-03-19',
                'delivery_date' => '2022-09-20',
                'user_id' => 1
            ],
            [
                'name' => 'Touchless Information Kiosk',
                'slug' => 'touchless-information-kiosk',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'start_date' => '2022-03-30',
                'delivery_date' => '2022-08-15',
                'user_id' => 1
            ],
            [
                'name' => 'Coral Reef recognition',
                'slug' => 'coral-reef-recognition',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'start_date' => '2022-03-10',
                'delivery_date' => '2022-09-20',
                'user_id' => 1
            ],
        ];

        foreach($data as $d){
            Project::create($d);
        }
    }
}
