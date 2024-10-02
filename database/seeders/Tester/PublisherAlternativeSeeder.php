<?php

namespace Database\Seeders\Tester;

use App\Models\AlternativeBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PublisherAlternativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/tester/alternative_books_202406062326.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            AlternativeBook::create([
                'borrowed_id' => $item['borrowed_id'],
                'publisher_id' => $item['publisher_id'],
                'year' => $item['year'],
            ]);
        }
    }

    private function readDataFromJson($filePath): array
    {
        return File::exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }
}
