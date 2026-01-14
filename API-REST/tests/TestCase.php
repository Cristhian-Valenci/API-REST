<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'verified', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'unverified', 'guard_name' => 'web']);
        
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'verified', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'unverified', 'guard_name' => 'api']);
    }

    protected function actingAsAdmin(): User
    {
        $admin = User::factory()->create([
            'password' => bcrypt('Password123.'),
        ]);
        
        $admin->assignRole('admin');
        
        Passport::actingAs(
            $admin,
            ['*'],
            'api'
        );
        
        return $admin;
    }
}