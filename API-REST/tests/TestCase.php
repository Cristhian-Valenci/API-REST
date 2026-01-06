<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles para que Spatie tenga los roles disponibles en los tests
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

}
