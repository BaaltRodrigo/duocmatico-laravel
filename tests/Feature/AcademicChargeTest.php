<?php

namespace Tests\Feature;

use App\Models\AcademicCharge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AcademicChargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_route_is_public(): void
    {
        AcademicCharge::factory()->count(10)->create();
        $response = $this->getJson(route('academic-charges.index'));

        $response->assertStatus(200);
    }

    public function test_show_route_is_public(): void
    {
        $academicCharge = AcademicCharge::factory()->create();

        $response = $this->getJson(route('academic-charges.show', $academicCharge));

        $response->assertStatus(200);
    }

    public function test_it_shows_available_charges_by_default(): void
    {
        AcademicCharge::factory()->create(['is_hidden' => true]);
        $availableCharge = AcademicCharge::factory()->create(['is_hidden' => false]);

        $response = $this->getJson(route('academic-charges.index'));

        // It will only send one charge, the one that is not hidden
        $response->assertJsonCount(1, 'charges');
        $response->assertJsonFragment(['id' => $availableCharge->id]);
    }

    // TODO: Test that require roles and permissions will be added here
    // Test to add when roles are implemented
    // - Only admin can create a charge
    // - Only admin can update a charge
    // - Only admin can delete a charge
    // - Only admin can make a charge hidden
    // - Only admin can make a charge visible
}
