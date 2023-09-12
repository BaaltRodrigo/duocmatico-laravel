<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\UseFirebaseUser;

class FirebaseAuthTest extends TestCase
{
    use RefreshDatabase, UseFirebaseUser;

    public function test_it_fails_without_token(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_pass_with_a_valid_user(): void
    {
        $this->actingAsFirebaseUser();

        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(Response::HTTP_OK);
    }
}
