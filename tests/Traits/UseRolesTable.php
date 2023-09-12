<?php

namespace Tests\Traits;

use Database\Seeders\RolesAndPermissionSeeder;

trait UseRolesTable
{
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionSeeder::class);
    }
}