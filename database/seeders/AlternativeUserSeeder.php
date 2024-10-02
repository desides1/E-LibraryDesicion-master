<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrowed;
use App\Models\Publisher;
use App\Models\CategoryBook;
use App\Models\AlternativeBook;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\File;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlternativeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/request_book/alternative.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $publisher_id = $this->getOrCreatePublisher($item);

            if ($publisher_id !== null) {
                for ($i = 0; $i < $item['QTY']; $i++) {
                    AlternativeBook::create([
                        'borrowed_id' => $this->getRandomPenerbitUserId($item['PRODI']),
                        'publisher_id' => $publisher_id,
                        'year' => $this->parsePublicationDate("2023"),
                    ]);
                }
            }
        }
    }

    private function getOrCreatePublisher($item)
    {
        if (empty($item['ISBN']) || empty($item['TITLE']) || empty($item['YEAR']) || empty($item['AUTHOR']) || empty($item['QTY']) || empty($item['PUBLISHER'])) {
            return null;
        }

        $existingPublisher = Publisher::where('isbn', $item['ISBN'])->first();

        if ($existingPublisher) {
            return $existingPublisher->id;
        }

        $user_id = $this->getOrCreateUser($item['PUBLISHER']);

        $book = new Publisher([
            'user_id' => $user_id,
            'category_id' => $this->getRandomCategory(),
            'title' => substr($item['TITLE'], 0, 250),
            'isbn' => $item['ISBN'],
            'publication_date' => $item['YEAR'],
            'abstract' => strtolower(substr($item['TITLE'], 0, 250)),
            'author' => $item['AUTHOR'],
            'price' => $item['price'],
            'publisher' => $item['PUBLISHER'],
            'available_stock' => $item['available_stock'],
            'type_book' => 'Cetak',
            'status' => 'Usulan Pemustaka',
            'image' => 'publisher-book/book-' . $this->sanitizeTitle(substr($item['TITLE'], 0, 250)) . '.jpg',
        ]);

        $book->save();

        return $book->id;
    }

    private function getOrCreateUser($publisherName)
    {
        $existingUser = User::where('name', $publisherName)->first();

        if ($existingUser) {
            return $existingUser->id;
        }

        $number_id = $this->generateRandomNumber(199103112000000000, 199903112000000000);

        $user = User::create([
            'name' => $publisherName,
            'email' => strtolower(str_replace(' ', '', $publisherName)) . '2024@gmail.com',
            'number_id' => $number_id,
            'password' => bcrypt((string)$number_id),
        ]);

        $user->assignRole('Penerbit');
        $user->givePermissionTo('Aktif');

        return $user->id;
    }

    private function parsePublicationDate($year)
    {
        $month = rand(1, 12);
        $day = rand(1, 28);
        return Carbon::create($year, $month, $day)->format('Y-m-d');
    }

    private function generateRandomNumber($min, $max)
    {
        return rand($min, $max);
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

    private function getRandomCategory()
    {
        $category = CategoryBook::whereIn('code', ["500", "600"])->get();

        return $category->isEmpty() ? 1 : $category->random()->id;
    }
    private function readDataFromJson($filePath): array
    {
        return File::exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }

    private function sanitizeTitle($title)
    {
        $title = preg_replace('/[^a-zA-Z0-9]/', '', $title);
        return strtolower($title);
    }
}
