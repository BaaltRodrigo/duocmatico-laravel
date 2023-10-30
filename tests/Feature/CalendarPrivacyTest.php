<?php

namespace Tests\Feature;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\UseFirebaseUser;

class CalendarPrivacyTest extends TestCase
{
    use RefreshDatabase, UseFirebaseUser;

    public function test_it_allows_guests_to_view_public_calendars(): void
    {
        $user = $this->createUser();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'is_public' => true
        ]);
        // Does not use any user to fetch the calendar

        $response = $this->getJson(route('calendars.show', $calendar->uuid));

        $response->assertOk();
    }

    public function test_it_allows_other_users_to_view_public_calendars(): void
    {
        $user1 = User::create(['id' => 'unused-user']);
        $calendar = Calendar::factory()->create([
            'user_id' => $user1->id,
            'is_public' => true
        ]);
        $firebaseUser = $this->createUser();
        $this->actingAsFirebaseUser($firebaseUser);

        $response = $this->getJson(route('calendars.show', $calendar->uuid));

        $response->assertOk();
    }

    public function test_it_allows_owners_to_view_own_calendars(): void
    {
        $user = $this->createUser();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'is_public' => false
        ]);
        $this->actingAsFirebaseUser($user);

        $response = $this->getJson(route('calendars.show', $calendar->uuid));

        $response->assertOk();
    }

    public function test_it_denies_guests_to_see_private_calendars(): void
    {
        $user = $this->createUser();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'is_public' => false
        ]);
        // Does not use any user to fetch the calendar

        $response = $this->getJson(route('calendars.show', $calendar->uuid));

        $response->assertForbidden();
    }

    public function test_it_denies_other_users_to_see_private_calendars(): void
    {
        $user = User::create(['id' => 'owner-user']);
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'is_public' => false
        ]);
        $firebaseUser = $this->createUser();
        $this->actingAsFirebaseUser($firebaseUser);

        $response = $this->getJson(route('calendars.show', $calendar->uuid));

        $response->assertForbidden();
    }

}