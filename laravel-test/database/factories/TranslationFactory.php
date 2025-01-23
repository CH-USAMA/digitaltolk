<?php


namespace Database\Factories;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale_id' => Locale::inRandomOrder()->first()->id,
            'key_name' => $this->faker->unique()->word,
            'content' => $this->faker->sentence,
            'tags' => json_encode($this->faker->words(3)),
        ];
    }
}
