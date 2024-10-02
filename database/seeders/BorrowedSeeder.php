<?php

namespace Database\Seeders;

use App\Models\Borrowed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BorrowedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/dosen.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $prodi = $item['prodi'];
            $jurusan = $item['jurusan'];

            foreach ($item['data'] as $dosen) {
                Borrowed::create([
                    'status' => 'Dosen',
                    'name' => $dosen['NAMA'],
                    'major' => $prodi,
                    'number_id' => $dosen['NIP/NIK']
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
