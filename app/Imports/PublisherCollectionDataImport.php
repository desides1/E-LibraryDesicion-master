<?php

namespace App\Imports;

use App\Models\CategoryBook;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PublisherCollectionDataImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    private $successCount = 0;
    private $failCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows->toArray() as $row) {
            $validator = Validator::make($row, [
                'kategori_buku' => 'nullable',
                'judul_buku' => 'required',
                'isbn_buku' => [
                    'required',
                    'regex:/^[a-zA-Z0-9]+(?:-[0-9a-zA-Z]+)*$/',
                    'min:9',
                    'max:20',
                    Rule::unique('books', 'isbn'),
                    Rule::unique('publishers', 'isbn'),
                ],
                'penerbit_buku' => 'required',
                'penulis_buku' => 'required',
                'tahun_terbit' => ['required', 'regex:/^\d{4}$/', 'gte:1900', 'lte:' . date('Y')],
                'jenis_buku' => 'nullable',
                'harga_buku' => 'required',
                'stok_buku' => 'nullable',
            ]);

            if ($validator->fails()) {
                $this->failCount++;
                continue;
            }

            if (!empty($row['no'])) {
                $data = [
                    'user_id' => auth()->user()->id,
                    'category_id' => $row['kategori_buku'] ? $this->getCategory($row['kategori_buku']) : 1,
                    'title' => $row['judul_buku'],
                    'isbn' => $row['isbn_buku'],
                    'publisher' => $row['penerbit_buku'],
                    'image' => 'publisher-book/book-' . $this->sanitizeTitle(substr($row['judul_buku'], 0, 255)) . '.jpg',
                    'publication_date' => $row['tahun_terbit'],
                    'type_book' =>  $row['jenis_buku'] ? $this->sanitizeTypeBook($row['jenis_buku']) : 'Cetak',
                    'status' => 'Aktif',
                    'author' => $this->sanitizeAuthor($row['penulis_buku']),
                    'price' => $row['harga_buku'],
                    'available_stock' => $row['stok_buku'] ? $row['stok_buku'] : 5,
                    'abstract' => strtolower($row['judul_buku']),
                ];

                Publisher::create($data);
                $this->successCount++;
            } else {
            }
        }
    }

    private function sanitizeAuthor($author)
    {
        return str_replace([';', ':'], ',', $author);
    }

    private function sanitizeTitle($title)
    {
        $title = preg_replace('/[^a-zA-Z0-9]/', '', $title);
        return strtolower($title);
    }

    private function sanitizeTypeBook($typeBook)
    {
        if ($typeBook === 'Cetak') {
            return 'Cetak';
        } else if ($typeBook === 'E-Book') {
            return 'E-Book';
        } else {
            return 'Cetak';
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailCount()
    {
        return $this->failCount;
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
            'Karya Umum' => '000',
            'Filsafat dan Psikologi' => '100',
            'Agama' => '200',
            'Ilmu Sosial' => '300',
            'Bahasa' => '400',
            'Ilmu Sains' => '500',
            'Ilmu Terapan' => '600',
            'Kesenian' => '700',
            'Kesusastraan' => '800',
            'Geografi dan Sejarah' => '900',
        ];

        if (array_key_exists($data, $mappedCategories)) {
            return $category->where('code', $mappedCategories[$data])->first()->id;
        } else {
            return 1;
        }
    }
}
