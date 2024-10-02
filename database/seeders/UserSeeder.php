<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->seedUsersFromJson('Pemustaka', 'user_mahasiswa.json', 'NAMA', 'NIM', '2020');
        $this->seedSingleUser('Admin', 'adminperpuspoliwangi@gmail.com', '199209212020122021', 'adminpoliwangi12345', 'Pustakawan');
        $this->seedUsersFromJson('Penerbit', 'mst_publisher.json', 'publisher_name', 'publisher_id', '2024');
    }

    private function seedUsersFromJson($role, $filePath, $nameKey, $numberIdKey, $emailSuffix)
    {
        $data = $this->readDataFromJson(database_path("seeders/data/$filePath"));
        $userCount = 0;

        foreach ($data as $item) {
            $name = str_replace(['.', ','], '', $item[$nameKey]);
            $name = ucwords(strtolower($name));

            $potentialEmail = strtolower(str_replace(' ', '', $name)) . $emailSuffix . '@gmail.com';
            $emailExists = User::where('email', $potentialEmail)->exists();

            if ($emailExists) {
                continue;
            }

            $randomNumber = $this->generateRandomNumber(199103112000000000, 199903112000000000);

            $user = User::create([
                'name' => $name,
                'email' => $potentialEmail,
                'number_id' => $randomNumber,
                'password' => bcrypt((string)$randomNumber),
            ]);

            $user->assignRole($role);
            $user->givePermissionTo('Aktif');

            $userCount++;

            if ($userCount >= 100) {
                break;
            }
        }
    }

    private function seedSingleUser($name, $email, $numberId, $password, $role)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'number_id' => $numberId,
            'password' => bcrypt($password),
        ]);

        $user->assignRole($role);
        $user->givePermissionTo('Aktif');
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

    private function generateRandomNumber($min, $max)
    {
        return mt_rand($min, $max);
    }
}
