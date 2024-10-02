<?php

namespace Database\Seeders;

use App\Models\AlternativeBook;
use App\Models\Borrowed;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlternativePaperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['2267', 'Teknologi Rekayasa Komputer', '2'],
            ['2268', 'Bisnis Digital', '20'],
            ['2272', 'Bisnis Digital', '1'],
            ['2274', 'Manajemen Bisnis Pariwisata', '2'],
            ['2275', 'Manajemen Bisnis Pariwisata', '2'],
            ['2282', 'Teknologi Pengolahan Hasil Ternak', '2'],
            ['2283', 'Teknologi Pengolahan Hasil Ternak', '2'],
            ['2294', 'Teknologi Rekayasa Manufaktur', '4'],
            ['2305', 'Teknik Sipil', '10'],
            ['2335', 'Teknologi Rekayasa Konstruksi Jalan dan Jembatan', '10'],
        ];

        foreach ($data as $value) {
            for ($i = 0; $i < (int)$value[2]; $i++) {
                AlternativeBook::create([
                    'publisher_id' => $this->getPublisher($value[0]),
                    'borrowed_id' => $this->getRandomPenerbitUserId($value[1]),
                    'year' => date('Y-m-d')
                ]);
            }
        }
    }

    public function getPublisher($data)
    {
        $publisher = Publisher::find($data);

        if (!$publisher) {
            return null;
        }

        return $publisher->id;
    }

    private function getRandomPenerbitUserId($data)
    {
        $penerbitUsers = Borrowed::where('status', 'Dosen')->where('Major', $data)->get();
        $this->faker = FakerFactory::create();

        if ($penerbitUsers->isEmpty()) {
            $newBorrowed = Borrowed::factory()->create([
                'name' => $this->faker->title() . ' ' . $this->faker->name,
                'status' => 'Dosen',
                'number_id' => $this->faker->numberBetween(199003112000000000, 199903112000000000),
                'major' => $data,
            ]);
            return $newBorrowed->id;
        }

        return $penerbitUsers->random()->id;
    }
}
