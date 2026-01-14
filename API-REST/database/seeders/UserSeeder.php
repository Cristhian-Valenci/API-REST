<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'verified']);

        
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        
        $user = User::factory()->verified()->create();
        $user->assignRole('verified');
    }
}