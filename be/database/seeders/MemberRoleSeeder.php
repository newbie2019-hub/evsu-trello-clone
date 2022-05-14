<?php

namespace Database\Seeders;

use App\Models\MemberRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberRoleSeeder extends Seeder
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
                'role' => 'Front End Developer'
            ],
            [
                'role' => 'Back End Developer'
            ],
            [
                'role' => 'Full Stack Developer'
            ],
            [
                'role' => 'Project Manager Developer'
            ],
            [
                'role' => 'QA Tester'
            ],
            [
                'role' => 'Client'
            ],
            [
                'role' => 'Mobile Developer'
            ],
            [
                'role' => 'Web Developer'
            ],
            [
                'role' => 'Cloud Infrastracture Engineer'
            ],
            [
                'role' => 'System Analyst'
            ],
            [
                'role' => 'Network Engineer'
            ],
            [
                'role' => 'Database Adminstrator'
            ],
            [
                'role' => 'IT Security Analyst'
            ],
            [
                'role' => 'IT Technician'
            ],
        ];

        foreach($data as $role) {
            MemberRole::create($role);
        }
    }
}
