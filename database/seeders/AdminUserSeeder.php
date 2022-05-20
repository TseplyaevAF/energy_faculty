<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Str::random(10);
        $email = 'energo_admin@admin.ru';
        Storage::disk('private')
            ->put('admin_password.txt', $email . "\n" . $password);
        User::factory()->createOne([
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
            'role_id' => User::ROLE_ADMIN
        ]);
    }
}
