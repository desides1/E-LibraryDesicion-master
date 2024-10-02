<?php

namespace Database\Seeders\Tester;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedSingleUser('Admin', 'adminperpuspoliwangi@gmail.com', '199209212020122021', 'adminpoliwangi12345', 'Pustakawan');
        $this->seedUsersFromJson('Penerbit', 'tester/user_tester.json', 'publisher_name', 'publisher_id', '2024');
    }


    private function seedUsersFromJson($role, $filePath, $nameKey, $numberIdKey, $emailSuffix)
    {
        $data = $this->readDataFromJson(database_path("seeders/data/$filePath"));
        $userCount = 0;

        foreach ($data as $item) {
            $user = User::create([
                'name' => $item['name'],
                'email' => $item['email'],
                'number_id' => $item['number_id'],
                'password' => bcrypt((string)$item['number_id']),
            ]);

            $user->assignRole($role);
            $user->givePermissionTo('Aktif');

            // $userCount++;

            // if ($userCount >= 100) {
            //     break;
            // }
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
}
