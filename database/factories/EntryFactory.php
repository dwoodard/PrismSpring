<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source'            => $this->faker->url,
            'type'              => $this->faker->randomElement(['pdf', 'mp3', 'mp4', 'website', 'text']),
            'title'             => $this->faker->sentence,
            'description'       => $this->faker->paragraph,
            'file_path'         => $this->faker->filePath,
            'file_size'         => $this->faker->numberBetween(1000, 1000000),
            'raw_data'          => $this->faker->text,
            'transformed_data'  => $this->faker->text,
            // Generate an array of 1536 random floats between 0 and 1
            'embeddings'        => array_map(fn() => $this->faker->randomFloat(6, 0, 1), range(1, 1536)),
            'metadata'          => ['example_key' => 'example_value'],
            'status'            => 'pending',
            'processed_at'      => null,
        ];
    }
}
