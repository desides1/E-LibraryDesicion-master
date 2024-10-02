<?php

namespace Database\Seeders;

use App\Models\SubCriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SubCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/sub_criteria.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $criteria_id = $item['criteria_id'];

            foreach ($item['subcriteria'] as $subitem) {
                SubCriteria::create([
                    'criteria_id' => $criteria_id,
                    'name_sub' => $subitem['name_sub'],
                    // 'description' => $subitem['description'],
                    'value' => $subitem['value']
                ]);
            }
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
