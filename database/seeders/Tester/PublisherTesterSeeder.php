<?php

namespace Database\Seeders\Tester;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Publisher;
use App\Models\CategoryBook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PublisherTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/tester/publishers_202406062328.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $book = new Publisher([
                'user_id' => $item['user_id'],
                'category_id' => $item['category_id'],
                'title' => $item['title'],
                'isbn' => $item['isbn'],
                'publication_date' => $item['publication_date'],
                'abstract' => $item['abstract'],
                'author' => $item['author'],
                'publisher' => $item['publisher'],
                'price' => $item['price'],
                'available_stock' => $item['available_stock'],
                'type_book' => $item['type_book'],
                'status' => $item['status'],
                'image' => 'publisher-book/book-' . $this->sanitizeTitle($item['title']) . '.jpg',
            ]);

            $book->save();
        }
    }

    private function parsePublicationDate($date)
    {
        return Carbon::parse($date['$date'])->format('Y-m-d');
    }

    private function formatArrayToString($array)
    {
        return implode(', ', $array);
    }

    private function generateRandomNumber($min, $max)
    {
        return rand($min, $max);
    }

    private function getRandomPenerbitUserId()
    {
        $penerbitUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'Penerbit'))->get();

        return $penerbitUsers->isEmpty() ? 1 : $penerbitUsers->random()->id;
    }

    private function getRandomCategory()
    {
        $category = CategoryBook::all();

        return $category->isEmpty() ? 1 : $category->random()->id;
    }
    private function readDataFromJson($filePath): array
    {
        return File::exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    }

    private function generateTypeBook()
    {
        $options = ["E-Book", "Cetak"];
        $randomIndex = array_rand($options);
        return $options[$randomIndex];
    }

    private function sanitizeTitle($title)
    {
        $title = preg_replace('/[^a-zA-Z0-9]/', '', $title);
        return strtolower($title);
    }
}
