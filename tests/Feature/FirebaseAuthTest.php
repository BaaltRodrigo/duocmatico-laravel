<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FirebaseAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_fails_without_token(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_pass_with_a_valid_user(): void
    {
        $this->actingAsFirebaseUser('duocmatico-admin@example.net', 'securePassword');

        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_OK);
    }
}
