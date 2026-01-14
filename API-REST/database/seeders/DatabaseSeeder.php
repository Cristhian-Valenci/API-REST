<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call(RoleSeeder::class);

        
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123.') 
        ]);
        $admin->assignRole('admin');

        
        $verified = User::factory()->create([
            'name' => 'Verified User',
            'email' => 'verified@example.com',
            'password' => Hash::make('Password123.')
        ]);
        $verified->assignRole('verified');
    }
}