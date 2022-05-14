<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserAccountSeeder::class,
            MemberRoleSeeder::class,
            ProjectSeeder::class,
            ProjectBoardSeeder::class,
            ProjectTaskSeeder::class,
            ProjectMemberSeeder::class,
        ]);
    }
}
