<?php

namespace Database\Seeders\Tester;

use Carbon\Carbon;
use App\Models\Borrowed;
use App\Models\AlternativeBook;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PublisherTesterCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/tester/publisher_tester3.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $qty = $this->generateRandomNumber(1, 12);

            if ($item['id'] !== null) {
                for ($i = 0; $i < $qty; $i++) {
                    AlternativeBook::create([
                        'borrowed_id' => $this->getRandomPenerbitUserId('Manajemen Bisnis Pariwisata'),
                        'publisher_id' => $item['id'],
                        'year' => $this->parsePublicationDate("2017"),
                    ]);
                }
            }
        }
    }

    private function parsePublicationDate($year)
    {
        $month = rand(1, 12);
        $day = rand(1, 28);
        return Carbon::create($year, $month, $day)->format('Y-m-d');
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

    private function generateRandomNumber($min, $max)
    {
        return rand($min, $max);
    }

    private function readDataFromJson($filePath): array
    {
        return File::exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }
}
