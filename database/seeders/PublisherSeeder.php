<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\CategoryBook;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/publisher_v1.json');
        $data = $this->readDataFromJson($filePath);

        foreach ($data as $item) {
            $book = new Publisher([
                'user_id' => $this->getRandomPenerbitUserId(),
                'category_id' => $this->getRandomCategory(),
                'title' => $item['title'],
                'isbn' => $item['isbn'],
                'publication_date' => $this->parsePublicationDate($item['publishedDate']),
                'abstract' => $item['shortDescription'],
                'author' => $this->formatArrayToString($item['authors']),
                'price' => $this->generateRandomNumber(45000, 235000),
                'available_stock' => $this->generateRandomNumber(1, 18),
                'type_book' => $this->generateTypeBook(),
                'status' => 'Aktif',
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
