<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowed>
 */
class BorrowedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = $this->faker->randomElement(['Dosen', 'Mahasiswa', 'Karyawan']);
        $name = $status === 'Mahasiswa' ? $this->faker->name : $this->faker->title() . ' ' . $this->faker->name;

        return [
            'name' => $name,
            'status' => $status,
            'number_id' => $status === 'Dosen' || $status === 'Karyawan' ?
                $this->faker->numberBetween(199003112000000000, 199903112000000000) :
                $this->faker->numberBetween(362055401000, 362055403000),
            'major' => $this->faker->randomElement(Major::pluck('name')->toArray()),
        ];
    }
}
