<?php

namespace Tests\Feature;

use App\Models\AcademicCharge;
use App\Models\Calendar;
use App\Models\Career;
use App\Models\School;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\UseFirebaseUser;
use Tests\Traits\UseRolesTable;

class CalendarTest extends TestCase
{
    use RefreshDatabase, UseFirebaseUser, UseRolesTable;

    public function test_model_can_morph_for_careers(): void
    {
        $user = $this->createUser();
        $career = Career::factory()->create();
        $calendar = Calendar::factory()->create([
            'calendarable_id' => $career->id,
            'calendarable_type' => get_class($career),
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('calendars', [
            'name' => $calendar->name,
            'description' => $calendar->description,
            'is_public' => $calendar->is_public,
            'options' => $calendar->options,
            'academic_charge_id' => $calendar->academic_charge_id,
            'calendarable_id' => $calendar->calendarable_id,
            'calendarable_type' => $calendar->calendarable_type,
            'user_id' => $calendar->user_id
        ]);
        $this->assertTrue(get_class($calendar->calendarable) === get_class($career));
    }

    public function test_model_can_morph_to_school(): void
    {
        $user = $this->createUser();
        $school = School::factory()->create();
        $calendar = Calendar::factory()->create([
            'calendarable_id' => $school->id,
            'calendarable_type' => get_class($school),
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('calendars', [
            'name' => $calendar->name,
            'description' => $calendar->description,
            'is_public' => $calendar->is_public,
            'options' => $calendar->options,
            'academic_charge_id' => $calendar->academic_charge_id,
            'calendarable_id' => $calendar->calendarable_id,
            'calendarable_type' => $calendar->calendarable_type,
            'user_id' => $calendar->user_id
        ]);
    }

    public function test_it_can_get_user_calendars(): void
    {
        $user = $this->createUser();
        Calendar::factory()->count(4)->create();
        $this->actingAsFirebaseUser();
            
        $response = $this->getJson(route('calendars.index'));

        $response->assertStatus(200);
    }

    public function test_it_allows_identified_users_to_create_calendars(): void
    {
        $user = $this->createUser();
        $charge = AcademicCharge::factory()->create();
        $career = Career::factory()->create();
        $data = [
            'name' => 'Test calendar',
            'description' => 'Test description',
            'uuid' => Str::uuid(),
            'is_public' => true,
            'academic_charge_id' => $charge->id,
            'calendarable_id' => $career->id,
            'calendarable_type' => 'career' // Front does not have the namespaced class
        ];
        $this->actingAsFirebaseUser();

        $response = $this->postJson(route('calendars.store'), $data);

        $response->assertStatus(201);
    }

    public function test_it_allows_owners_to_update_calendars(): void
    {
        $user = $this->createUser();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id
        ]);
        $data = [
            'name' => 'Test calendar',
        ];
        $this->actingAsFirebaseUser();

        $response = $this->putJson(route('calendars.update', $calendar->uuid), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('calendars', [
            'name' => $data['name'],
            'id' => $calendar->id
        ]);
    }

    public function test_it_allows_owners_to_delete_calendars(): void
    {
        $user = $this->createUser();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id
        ]);
        $this->actingAsFirebaseUser();

        $response = $this->deleteJson(route('calendars.destroy', $calendar->uuid));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('calendars', [
            'id' => $calendar->id
        ]);
    }

    public function test_it_allows_owners_to_add_sections_to_calendar(): void
    {
        $user = $this->createUser();
        $section = Section::factory()->create();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'academic_charge_id' => $section->academic_charge_id,
            'calendarable_type' => get_class($section->career),
            'calendarable_id' => $section->career->id
        ]);
        $this->actingAsFirebaseUser();

        $response = $this->postJson(route('calendars.sections.store', $calendar->uuid), [
            'section_id' => $section->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('calendar_section', [
            'calendar_id' => $calendar->id,
            'section_id' => $section->id
        ]);
    }

    public function test_it_allows_owners_to_delete_calendar_section(): void
    {
        $user = $this->createUser();
        $section = Section::factory()->create();
        $calendar = Calendar::factory()->create([
            'user_id' => $user->id,
            'academic_charge_id' => $section->academic_charge_id,
            'calendarable_type' => get_class($section->career),
            'calendarable_id' => $section->career->id
        ]);
        $calendar->sections()->attach($section);
        $this->actingAsFirebaseUser();

        $response = $this->deleteJson(route('calendars.sections.destroy', [$calendar->uuid, $section->id]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('calendar_section', [
            'calendar_id' => $calendar->id,
            'section_id' => $section->id
        ]);
    }
}
