<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class UserPreferenceFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_user_preferences()
    {
        // Create a user
        $user = User::factory()->create();

        // Use Sanctum to authenticate as this user
        Sanctum::actingAs($user);

        // Make a POST request to set preferences
        $response = $this->postJson('/api/user/preferences', [
            'preferred_sources' => 'The Guardian,New York Times',
            'preferred_categories' => 'Technology,World News',
            'preferred_authors' => 'John Doe,Jane Smith',
        ]);

        // Assert status 200
        $response->assertStatus(200);

        // Assert preferences were set successfully
        $response->assertJsonFragment(['message' => 'Preferences updated successfully.']);
    }

    /** @test */
    public function it_can_get_user_preferences()
    {
        // Create a user with preferences
        $user = User::factory()->create();
        $user->preferences()->create([
            'preferred_sources' => 'The Guardian,New York Times',
            'preferred_categories' => 'Technology,World News',
            'preferred_authors' => 'John Doe,Jane Smith',
        ]);

        // Use Sanctum to authenticate as this user
        Sanctum::actingAs($user);

        // Make a GET request to retrieve preferences
        $response = $this->getJson('/api/user/preferences');

        // Assert status 200
        $response->assertStatus(200);

        // Assert the preferences are correct
        $response->assertJsonFragment(['preferred_sources' => 'The Guardian,New York Times']);
    }
}
