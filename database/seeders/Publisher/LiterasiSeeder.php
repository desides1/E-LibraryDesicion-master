<?php

namespace Database\Seeders\Publisher;

use App\Models\CategoryBook;
use App\Models\Publisher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;

class LiterasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/publisher/literasi_book.json');
        $data = $this->readDataFromJson($filePath);

        $publisherName = 'Literasi Number';
        $existingUser = User::where('name', $publisherName)->first();

        if ($existingUser) {
            $user_id = $existingUser->id;
            $user_name = $existingUser->name;
        } else {
            $number_id = $this->generateRandomNumber(199103112000000000, 199903112000000000);

            $user = User::create([
                'name' => $publisherName,
                'email' => strtolower(str_replace(' ', '', $publisherName)) . '2024@gmail.com',
                'number_id' => $number_id,
                'password' => bcrypt((string)$number_id),
            ]);

            $user->assignRole('Penerbit');
            $user->givePermissionTo('Aktif');

            $user_id = $user->id;
            $user_name = $user->name;
        }

        foreach ($data as $item) {
            if (empty($item['title']) || empty($item['ISBN']) || empty($item['Terbit']) || empty($item['Pengarang']) || empty($item['Harga'])) {
                continue;
            }

            if (Publisher::where('isbn', $item['ISBN'])->exists()) {
                continue;
            }

            $book = new Publisher([
                'user_id' => $user_id,
                'category_id' => $this->getCategory($item['Kategori']),
                'title' => substr($item['title'], 0, 255),
                'isbn' => $item['ISBN'],
                'publication_date' => $this->parsePublicationDate($item['Terbit']),
                'abstract' => strtolower($item['title']),
                'author' => $item['Pengarang'],
                'price' => str_replace(['.', ','], '', $item['Harga']),
                'publisher' => $user_name,
                'available_stock' => $this->generateRandomNumber(1, 18),
                'type_book' => 'Cetak',
                'status' => 'Aktif',
                'image' => 'publisher-book/book-' . $this->sanitizeTitle(substr($item['title'], 0, 255)) . '.jpg',
            ]);

            $book->save();
        }
    }

    private function parsePublicationDate($year)
    {
        return Carbon::create($year)->format('Y');
    }

    private function generateRandomNumber($min, $max)
    {
        return rand($min, $max);
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

    private function getCategory($data)
    {
        $category = CategoryBook::all();
        $mappedCategories = [
            'Agama' => '200',
            'Agama dan Filsafat' => '200',
            'Pendidikan' => '800',
            'Bahasa' => '400',
            'Ekonomi' => '300',
            'Hukum' => '300',
            'Kesehatan' => '500',
            'Sains dan Teknologi' => '500',
            'Sosial Humaniora' => '300'
        ];

        if (array_key_exists($data, $mappedCategories)) {
            return $category->where('code', $mappedCategories[$data])->first()->id;
        } else {
            return 1;
        }
    }
}
