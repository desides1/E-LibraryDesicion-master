<?php

namespace Database\Seeders;

use App\Models\Borrowed;
use App\Models\AlternativeBook;
use Illuminate\Database\Seeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MajorSeeder;
use Database\Seeders\BorrowedSeeder;
use Database\Seeders\CriteriaSeeder;
use Database\Seeders\PublisherSeeder;
use Database\Seeders\BudgetBookSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\SubCriteriaSeeder;
use Database\Seeders\ClassificationSeeder;
use Database\Seeders\AlternativeUserSeeder;
use Database\Seeders\Publisher\GosyenSeeder;
use Database\Seeders\Tester\BookTesterSeeder;
use Database\Seeders\Tester\UserTesterSeeder;
use Database\Seeders\Publisher\AndiBookSeeder;
use Database\Seeders\Publisher\LiterasiSeeder;
use Database\Seeders\Publisher\AirlanggaSeeder;
use Database\Seeders\Publisher\GavaMediaSeeder;
use Database\Seeders\AlternativePaperUserSeeder;
use Database\Seeders\Publisher\BumiAksaraSeeder;
use Database\Seeders\Publisher\RosdakaryaSeeder;
use Database\Seeders\Publisher\SalembaEmpatSeeder;
use Database\Seeders\Tester\PublisherTesterSeeder;
use Database\Seeders\Tester\PublisherTesterBSeeder;
use Database\Seeders\Tester\PublisherTesterCSeeder;
use Database\Seeders\Tester\PublisherTesterDSeeder;
use Database\Seeders\Tester\PublisherTesterESeeder;
use Database\Seeders\Publisher\SalembaInfotekSeeder;
use Database\Seeders\Publisher\SalembaTeknikaSeeder;
use Database\Seeders\Publisher\MitraWacanaMediaSeeder;
use Database\Seeders\Tester\PublisherAlternativeSeeder;
use Database\Seeders\Publisher\IndomediaPustakaPublisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CriteriaSeeder::class);
        $this->call(SubCriteriaSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(UserTesterSeeder::class);
        $this->call(ClassificationSeeder::class);
        // $this->call(BookSeeder::class);
        $this->call(BookTesterSeeder::class);
        // $this->call(LiterasiSeeder::class);
        // $this->call(GavaMediaSeeder::class);
        // $this->call(BumiAksaraSeeder::class);
        // $this->call(GosyenSeeder::class);
        // $this->call(AndiBookSeeder::class);
        // $this->call(SalembaEmpatSeeder::class);
        // $this->call(SalembaTeknikaSeeder::class);
        // $this->call(SalembaInfotekSeeder::class);
        // $this->call(AirlanggaSeeder::class);
        // $this->call(IndomediaPustakaPublisher::class);
        // $this->call(RosdakaryaSeeder::class);
        // $this->call(MitraWacanaMediaSeeder::class);
        // $this->call(PublisherSeeder::class);
        $this->call(PublisherTesterSeeder::class);
        $this->call(BudgetBookSeeder::class);
        $this->call(MajorSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(BorrowedSeeder::class);

        // Borrowed::factory(100)->create();
        $this->call(AlternativeUserSeeder::class);
        $this->call(AlternativeDPSeeder::class);
        // $this->call(AlternativePaperUserSeeder::class);
        $this->call(PublisherAlternativeSeeder::class);
        // AlternativeBook::factory(190)->create();
    }
}
