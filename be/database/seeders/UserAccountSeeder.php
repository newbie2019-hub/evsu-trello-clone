<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userinfo = UserInfo::create([
            'first_name' => 'John',
            'middle_name' => '',
            'last_name' => 'Doe',
            'birthday' => '1999-09-23',
            'contact_number' => '09123456789',
            'gender' => 'Male',
            'address' => 'Brgy. 99 - D Matagpuan Street, Tacloban City, Leyte',
        ]);

        User::create([
            'email' => 'sampleemail@gmail.com',
            'password' => Hash::make('123123'),
            'user_info_id' => $userinfo->id
        ]);

        $userinfo = UserInfo::create([
            'first_name' => 'Renz',
            'middle_name' => '',
            'last_name' => 'Uyson',
            'birthday' => '1999-01-01',
            'contact_number' => '09123456789',
            'gender' => 'Male',
            'address' => 'Brgy. 99 - D Matagpuan Street, Tacloban City, Leyte',
        ]);

        User::create([
            'email' => 'sampleemail2@gmail.com',
            'password' => Hash::make('123123'),
            'user_info_id' => $userinfo->id
        ]);

        $userinfo = UserInfo::create([
            'first_name' => 'John',
            'middle_name' => '',
            'last_name' => 'Mark',
            'birthday' => '1999-01-01',
            'contact_number' => '09123456789',
            'gender' => 'Male',
            'address' => 'Brgy. 99 - D Matagpuan Street, Tacloban City, Leyte',
        ]);

        User::create([
            'email' => 'sampleemail3@gmail.com',
            'password' => Hash::make('123123'),
            'user_info_id' => $userinfo->id
        ]);

        $userinfo = UserInfo::create([
            'first_name' => 'Joe',
            'middle_name' => '',
            'last_name' => 'Biden',
            'birthday' => '1999-01-01',
            'contact_number' => '09123456789',
            'gender' => 'Male',
            'address' => 'Brgy. 99 - D Matagpuan Street, Tacloban City, Leyte',
        ]);

        User::create([
            'email' => 'sampleemail4@gmail.com',
            'password' => Hash::make('123123'),
            'user_info_id' => $userinfo->id
        ]);
    }
}
