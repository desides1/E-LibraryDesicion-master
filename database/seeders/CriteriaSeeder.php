<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/criteria.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            Criteria::create([
                'code' => $item['code'],
                'name' => $item['name'],
                'weight' => $item['weight'],
                'type' => $item['type'],
                'status' => 'Aktif',
                'sub_criterias' => $item['sub_criterias'],
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
