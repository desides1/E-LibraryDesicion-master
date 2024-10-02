<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\CategoryBook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/biblio.json');
        $data = $this->readDataFromJson($filePath);

        $fileBibAu = $this->readDataFromJson(database_path('seeders/data/biblio_author.json'));
        $fileAuthor = $this->readDataFromJson(database_path('seeders/data/mst_author.json'));

        $userCount = 0;

        foreach ($data as $item) {
            $inputDate = $item['input_date'];
            $publicationYear = date('Y', strtotime($inputDate));
            if ($publicationYear < 2011 || $publicationYear > 2024) {
                continue;
            }

            $isbnIssn = $item['isbn_issn'];
            if (empty($isbnIssn)) {
                continue;
            }

            $publishYear = $item['publish_year'];
            if (empty($publishYear) || !strtotime($publishYear)) {
                continue;
            }

            $callNumber = $item['call_number'];
            if (!ctype_digit(substr($callNumber, 0, 1))) {
                continue;
            }

            $title = $item['title'];
            if (stripos($title, 'prosiding') !== false || stripos($title, 'seminar') !== false || stripos($title, 'jurnal') !== false) {
                continue;
            }

            $authors = collect($fileBibAu)->where('biblio_id', $item['biblio_id'])->pluck('author_id');

            $authorNames = collect($fileAuthor)
                ->whereIn('author_id', $authors)
                ->pluck('author_name')
                ->toArray();

            $authorString = implode(', ', $authorNames);

            if (empty($authorString)) {
                continue;
            }

            if (empty($item['classification'])) {
                continue;
            }

            Book::create([
                'user_id' => $this->getDataAdmin(),
                'publisher_id' => $this->getRandomPenerbitUserId(),
                'category_id' => $this->getRandomCategory($item['classification']),
                'title' => str_replace(['"', "'"], '', substr($item['title'], 0, 255)),
                'isbn' => $item['isbn_issn'],
                'publication_date' => $item['publish_year'],
                'abstract' => (isset($item['notes']) && $item['notes'] !== '') ? strtolower(str_replace(['"', "'"], '', $item['notes'])) : strtolower(str_replace(['"', "'"], '', substr($item['title'], 0, 255))),
                'author' => $authorString,
                'price' => $this->generateRandomNumber(45000, 235000),
                'available_stock' => $this->generateRandomNumber(1, 10),
                'type_book' => "Cetak",
                'status' => 'Aktif',
                'image' => 'library-book/book-' . $this->sanitizeTitle(substr($item['title'], 0, 255)) . '.jpg',
            ]);


            // 'abstract' => $item['shortDescription'],
            // 'author' => $this->formatArrayToString($item['authors']),
            // 'publication_date' => $this->parsePublicationDate($item['publishedDate']),

            // $userCount++;

            // if ($userCount >= 11) {
            //     break;
            // }
        }
    }

    private function parsePublicationDate($date)
    {
        return Carbon::parse($date['$date'])->format('Y');
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

    private function getRandomCategory($data)
    {
        $category = CategoryBook::all();

        $mappedCategories = [
            '0' => '000',
            '1' => '100',
            '2' => '200',
            '3' => '300',
            '4' => '400',
            '5' => '500',
            '6' => '600',
            '7' => '700',
            '8' => '800',
            '9' => '900',
        ];

        $firstDigit = substr($data, 0, 1);

        if (array_key_exists($firstDigit, $mappedCategories)) {
            return $category->where('code', $mappedCategories[$firstDigit])->first()->id;
        } else {
            return 1;
        }
    }

    private function getDataAdmin()
    {
        $admin = User::whereHas('roles', fn ($query) => $query->where('name', 'Pustakawan'))->first();

        return $admin ? $admin->id : null;
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
