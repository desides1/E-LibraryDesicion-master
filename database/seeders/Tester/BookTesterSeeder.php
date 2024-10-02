<?php

namespace Database\Seeders\Tester;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/tester/book_tester.json');
        $data = $this->readDataFromJson($filePath);

        $userCount = 0;

        foreach ($data as $item) {
            Book::create([
                'user_id' => $item['user_id'],
                'publisher_id' => $item['publisher_id'],
                'category_id' => $item['category_id'],
                'title' => $item['title'],
                'isbn' => $item['isbn'],
                'publication_date' => $item['publication_date'],
                'abstract' => $item['abstract'],
                'author' => $item['author'],
                'price' => $item['price'],
                'available_stock' => $item['available_stock'],
                'type_book' => "Cetak",
                'status' => 'Aktif',
                'image' => 'library-book/book-' . $this->sanitizeTitle(substr($item['title'], 0, 255)) . '.jpg',
            ]);

            // $userCount++;

            // if ($userCount >= 11) {
            //     break;
            // }
        }
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
