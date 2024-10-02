<?php

namespace Database\Factories;

use App\Models\Major;
use App\Models\Borrowed;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlternativeBook>
 */
class AlternativeBookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startDate = '2024-01-01';
        $endDate = now()->format('Y-m-d');

        return [
            'borrowed_id' => $this->faker->randomElement(Borrowed::pluck('id')->toArray()),
            'publisher_id' => $this->faker->randomElement(Publisher::where('category_id', 6)->pluck('id')->toArray()),
            'year' => $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d')
        ];
    }
}
