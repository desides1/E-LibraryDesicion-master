<?php

namespace Database\Seeders\Publisher;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Publisher;
use App\Models\CategoryBook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalembaTeknikaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = database_path('seeders/data/publisher/salemba_teknika_book.json');
        $data = $this->readDataFromJson($filePath);

        $publisherName = 'Salemba Teknika';
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
            'Agama Islam' => '200',
            'Filsafat' => '100',
            'Agama dan Filsafat' => '200',
            'Fiksi' => '200',
            'Pendidikan' => '800',
            'Bahasa' => '400',
            'Manajemen' => '300',
            'Ekonomi' => '300',
            'Pajak' => '300',
            'Bisnis' => '300',
            'Politik' => '300',
            'Psikologi' => '300',
            'Psikologi' => '300',
            'Sosiologi' => '300',
            'Akutansi' => '300',
            'Ekologi' => '300',
            'Hukum' => '300',
            'Undang-Undang' => '300',
            'Keperawatan' => '500',
            'Kebidanan' => '500',
            'Kesehatan' => '500',
            'Medical' => '500',
            'Kesehatan Masyarakat' => '500',
            'Kesehatan Umum' => '500',
            'Kedokteran' => '500',
            'Teknik' => '500',
            'Statistik' => '500',
            'Gizi' => '500',
            'Biologi' => '500',
            'Sains dan Teknologi' => '500',
            'Sains' => '500',
            'Teknologi Informasi' => '500',
            'Robotika' => '500',
            'Elektronika/Listrik' => '500',
            'Komputer' => '500',
            'Pertanian' => '500',
            'Sosial Humaniora' => '300',
            'Sosial' => '300',
            'Pariwisata' => '600',
            'Pariwisata & Perhotelan' => '600',
            'Administrasi' => '600',
            'Tata Negara' => '600',
            'Kamus' => '600',
            'Kehutanan' => '600',
            'Penelitian' => '600',
            'Komunikasi' => '600',
            'Ligkungan' => '900',
        ];

        if (array_key_exists($data, $mappedCategories)) {
            return $category->where('code', $mappedCategories[$data])->first()->id;
        } else {
            return 1;
        }
    }
}
