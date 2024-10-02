<?php

namespace Database\Seeders;

use App\Models\CategoryBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/classification.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            CategoryBook::create([
                'code' => $item['code'],
                'name' => $item['name'],
            ]);
        }
    }

    private function readDataFromJson($filePath): array
    {
        $data = [];

        if (File::exists($filePath)) {
            $jsonString = file_get_contents($filePath);
            $data = json_decode($jsonString, true);
        }

        return $data;
    }
}
