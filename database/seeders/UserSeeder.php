<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $new = new \App\Actions\Fortify\CreateNewUser();
        $new->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => 'Password123@',
            'password_confirmation' => 'Password123@',
            'email' => 'admin@admin.com',
            'role_id' => 'admin',
            'telegram_bot_token' => null,
            'telegram_chat_id' => null,
        ]);
    }
}
