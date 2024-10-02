<?php

namespace Database\Seeders;

use App\Models\BudgetBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BudgetBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/budget.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            BudgetBook::create([
                'price' => $item['price'],
                'ppn' => $item['ppn'],
                'year' => $item['year'],
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
