# Translation Management System

This project provides a simple translation management system where you can manage `locales` and their associated `translations`. The translations are stored in a database, and the system allows you to store, update, and export translations. It also includes migrations for setting up the database structure.

## Features

- **Locales Management**: Create and manage multiple locales (languages).
- **Translations**: Store translations associated with specific locales.
- **Export Translations**: Export translations for a locale into a usable format.
- **Database Migration**: Migrations to set up the database structure.

## Prerequisites

Before you begin, ensure you have the following installed:

- PHP >= 8.4
- Composer
- Laravel 11
- MySQL (or any other database supported by Laravel)

## Installation

### Step 1: Clone the repository

Clone the repository to your local machine:


Project Structure
app/Models/Locale.php: The Locale model representing the locales in the system.
app/Models/Translation.php: The Translation model representing translations tied to locales.
database/migrations: Contains migration files to create tables for locales and translations.
routes/web.php: Routes for storing and managing translations and locales.


### API Endpoints
### 1. Store a New Translation
### POST /translations

### Request Body:

{
    "locale": "en",
    "key_name": "greeting",
    "content": "Hello, world",
    "tags": ["welcome", "general"]
}

### Response:
{
    "id": 1,
    "locale_id": 1,
    "key_name": "greeting",
    "content": "Hello, world",
    "tags": "[\"welcome\", \"general\"]",
    "created_at": "2025-01-23T10:23:51.000000Z",
    "updated_at": "2025-01-23T10:23:51.000000Z"
}

### 2. Update a Translation
### PUT /translations/{id}

Request Body:

{
    "content": "Hi, world",
    "tags": ["greetings"]
}
### Response:

{
    "id": 1,
    "locale_id": 1,
    "key_name": "greeting",
    "content": "Hi, world",
    "tags": "[\"greetings\"]",
    "created_at": "2025-01-23T10:23:51.000000Z",
    "updated_at": "2025-01-23T10:45:51.000000Z"
}


### 3. Export Translations for a Locale
### GET /translations/export/{locale}

### Response:

### Exports all translations for the specified locale in JSON format.

[
    {
        "key_name": "greeting",
        "content": "Hello, world",
        "tags": "[\"welcome\", \"general\"]"
    },
    {
        "key_name": "farewell",
        "content": "Goodbye",
        "tags": "[\"farewell\"]"
    }
]



### This Translation Management System provides an easy-to-use structure to manage locales and translations in your Laravel application. It allows CRUD operations and exporting translations for different locales.




### Test Case 1: Creating a Translation
### Purpose: This test ensures that a valid request creates a translation entry successfully.

### Input: A valid locale, key_name, content, and tags.
### Expected Outcome: The translation is created and stored in the database with the correct data.

public function it_creates_a_translation_successfully()
{
    $data = [
        'locale' => 'en',
        'key_name' => 'greetings',
        'content' => 'Hello World',
        'tags' => ['welcome', 'general'],
    ];

    $response = $this->json('POST', '/api/translations', $data);

    $response->assertStatus(201);
    $response->assertJsonFragment([
        'locale_id' => 1,
        'key_name' => 'greetings',
        'content' => 'Hello World',
        'tags' => json_encode(['welcome', 'general']),
    ]);

    $this->assertDatabaseHas('translations', [
        'key_name' => 'greetings',
        'content' => 'Hello World',
        'tags' => json_encode(['welcome', 'general']),
    ]);
}


### Test Case 2: Validating Required Fields
### Purpose: This test checks that the API validates required fields such as key_name and content.

Input: Missing key_name or content.
Expected Outcome: The API should return a 422 status with a validation error.

public function it_validates_required_fields()
{
    $data = [
        'locale' => 'en',
        'content' => 'Hello World',
    ];

    $response = $this->json('POST', '/api/translations', $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['key_name']);
}
### Test Case 3: Validating Locale Existence
### Purpose: This test ensures that the API validates if the provided locale exists in the system.

Input: A non-existent locale (fr).
Expected Outcome: The API should return a 422 status with a validation error for locale.

public function it_validates_locale_exists()
{
    $data = [
        'locale' => 'fr',
        'key_name' => 'greetings',
        'content' => 'Bonjour le monde',
    ];

    $response = $this->json('POST', '/api/translations', $data);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['locale']);
}
### Test Case 4: Handling Null Tags
### Purpose: This test checks that if the tags field is null, it gets stored as an empty array.

Input: A null value for tags.
Expected Outcome: The tags should be stored as an empty array in the database.

public function it_handles_null_tags()
{
    $data = [
        'locale' => 'en',
        'key_name' => 'goodbye',
        'content' => 'Goodbye World',
        'tags' => null,
    ];

    $response = $this->json('POST', '/api/translations', $data);
    $response->assertStatus(201);
    $response->assertJsonFragment(['tags' => json_encode([])]);

    $this->assertDatabaseHas('translations', [
        'key_name' => 'goodbye',
        'content' => 'Goodbye World',
        'tags' => json_encode([]),
    ]);
}
