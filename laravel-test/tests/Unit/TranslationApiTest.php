<?php

namespace Tests\Unit;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    // Create a test locale before each test
    protected function setUp(): void
    {
        parent::setUp();

        // Create a dummy locale
        // Locale::create(['code' => 'es','name'=> 'english']);
    }

    /** @test */
    public function it_creates_a_translation_successfully()
    {
        // Valid data for the test
        $data = [
            'locale' => 'en',
            'key_name' => 'greetings',
            'content' => 'Hello World',
            'tags' => ['welcome', 'general'],
        ];

        // Send POST request to the translation store API
        $response = $this->json('POST', '/api/translations', $data);

        // Assert the response status is 201 (created)
        $response->assertStatus(201);

        // Assert the response contains the correct data
        $response->assertJsonFragment([
            'locale_id' => 1,
            'key_name' => 'greetings',
            'content' => 'Hello World',
            'tags' => json_encode(['welcome', 'general']),
        ]);

        // Ensure the translation is stored in the database
        $this->assertDatabaseHas('translations', [
            'key_name' => 'greetings',
            'content' => 'Hello World',
            'tags' => json_encode(['welcome', 'general']),
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Missing key_name
        $data = [
            'locale' => 'en',
            'content' => 'Hello World',
        ];

        $response = $this->json('POST', '/api/translations', $data);

        // Assert the response status is 422 (validation error)
        $response->assertStatus(422);

        // Assert the validation error contains the correct message for key_name
        $response->assertJsonValidationErrors(['key_name']);
    }

    /** @test */
    public function it_validates_locale_exists()
    {
        // Use a non-existing locale
        $data = [
            'locale' => 'fr',  // 'fr' locale doesn't exist in the test setup
            'key_name' => 'greetings',
            'content' => 'Bonjour le monde',
        ];

        $response = $this->json('POST', '/api/translations', $data);

        // Assert the response status is 422 (validation error)
        $response->assertStatus(422);

        // Assert the validation error contains the correct message for locale
        $response->assertJsonValidationErrors(['locale']);
    }

    /** @test */
    public function it_handles_null_tags()
    {
        // Data with null tags
        $data = [
            'locale' => 'en',
            'key_name' => 'goodbye',
            'content' => 'Goodbye World',
            'tags' => null,
        ];

        // Send POST request to the translation store API
        $response = $this->json('POST', '/api/translations', $data);

        // Assert the response status is 201 (created)
        $response->assertStatus(201);

        // Assert the tags are stored as an empty array
        $response->assertJsonFragment([
            'tags' => json_encode([]),
        ]);

        // Ensure the translation is stored in the database
        $this->assertDatabaseHas('translations', [
            'key_name' => 'goodbye',
            'content' => 'Goodbye World',
            'tags' => json_encode([]),
        ]);
    }
}
