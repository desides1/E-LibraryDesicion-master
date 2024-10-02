<?php

namespace App\Http\Controllers\Backend\Recommendation;

use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Publisher;
use App\Models\SubCriteria;
use PhpParser\Node\Stmt\Switch_;

class MooraController extends Controller
{
    public function getAlternativeBook($alternative)
    {
        $alternativeData = [];

        $isbnList = $alternative->pluck('publisher.isbn')->unique();
        $activePublishers = Publisher::where('status', 'Aktif')->whereIn('isbn', $isbnList)->get()->keyBy('isbn');

        $books = Book::whereIn('isbn', $isbnList)->get();

        foreach ($alternative as $decision) {
            $publisherIsbn = $decision->publisher->isbn;
            $publisher = $activePublishers[$publisherIsbn] ?? null;
            $publisherStock = $publisher ? $publisher->available_stock : 0;
            $book = $books->firstWhere('isbn', $decision->publisher->isbn);

            $tempData = [
                'title' => $decision->publisher->title,
                'publisher-id' => $decision->publisher->id,
                'year-publication' => $decision->publisher->publication_date,
                'book-price' => $decision->publisher->price,
                'publisher_id' => $decision->publisher->id,
                'library-stock' => $book ? $book->available_stock : 0,
                'publisher-stock' => $publisherStock,
                'request-book' => $decision->request_book_count ?? 0
            ];

            $alternativeData[] = $tempData;
        }

        return $alternativeData;
    }

    public function convertPublicationYearValue($year, $subCriteria)
    {
        $currentYear = date('Y');
        $yearCount = $year;
        $value = 0;

        foreach ($subCriteria as $subCriterion) {
            if (strpos($subCriterion['name_sub'], '>') !== false) {
                $upperBound = (int) substr($subCriterion['name_sub'], strpos($subCriterion['name_sub'], '>') + 1);
                if ($currentYear - $yearCount > $upperBound) {
                    $value = $subCriterion['value'];
                }
            } else if (strpos($subCriterion['name_sub'], '<') !== false) {
                $lowerBound = (int) substr($subCriterion['name_sub'], strpos($subCriterion['name_sub'], '<') + 1);
                if ($currentYear - $yearCount < $lowerBound) {
                    $value = $subCriterion['value'];
                }
            } else {
                $range = explode(' - ', $subCriterion['name_sub']);
                $lowerBound = (int) $range[0];
                $upperBound = (int) $range[1];
                if ($currentYear - $yearCount >= $lowerBound && $currentYear - $yearCount <= $upperBound) {
                    $value = $subCriterion['value'];
                    break;
                }
            }
        }

        return $value;
    }

    public function convertRequestBookValue($requestBook, $subCriteria)
    {
        $requestBookCount = $requestBook;
        $value = 0;

        foreach ($subCriteria as $subCriterion) {
            if (strpos($subCriterion['name_sub'], '>') !== false) {
                $upperBound = (int) substr($subCriterion['name_sub'], strpos($subCriterion['name_sub'], '>') + 1);
                if ($requestBookCount > $upperBound) {
                    $value = $subCriterion['value'];
                }
            } else {
                $range = explode(' - ', $subCriterion['name_sub']);
                $lowerBound = (int) $range[0];
                $upperBound = (int) $range[1];
                if ($requestBookCount >= $lowerBound && $requestBookCount <= $upperBound) {
                    $value = $subCriterion['value'];
                    break;
                }
            }
        }

        return $value;
    }

    public function convertPriceValue($price, $subCriteria)
    {
        $priceCount = $price;
        $value = 0;

        foreach ($subCriteria as $subCriterion) {
            if (strpos($subCriterion['name_sub'], '>') !== false) {
                $upperBound = (int) substr($subCriterion['name_sub'], strpos($subCriterion['name_sub'], '>') + 1);
                if ($priceCount > $upperBound) {
                    $value = $subCriterion['value'];
                }
            } else if (strpos($subCriterion['name_sub'], '<') !== false) {
                $lowerBound = (int) substr($subCriterion['name_sub'], strpos($subCriterion['name_sub'], '<') + 1);
                if ($priceCount < $lowerBound) {
                    $value = $subCriterion['value'];
                }
            } else {
                $range = explode(' - ', $subCriterion['name_sub']);
                $lowerBound = (int) $range[0];
                $upperBound = (int) $range[1];
                if ($priceCount >= $lowerBound && $priceCount <= $upperBound) {
                    $value = $subCriterion['value'];
                    break;
                }
            }
        }

        return $value;
    }

    public function getDecisionMatrixBook($decision)
    {
        $criteria = Criteria::with('subCriteria')
            ->whereIn('name', ['Tahun Terbit', 'Jumlah Usulan Buku', 'Harga Buku'])
            ->get()
            ->keyBy('name');

        $subCriteria = [
            'Tahun Terbit' => [],
            'Jumlah Usulan Buku' => [],
            'Harga Buku' => [],
        ];

        $criteria->each(function ($item, $key) use (&$subCriteria) {
            foreach ($item->subCriteria as $sub) {
                $subCriteria[$item->name][] = $sub->toArray();
            }
        });

        foreach ($decision as &$data) {
            $data['year-publication-value'] = $this->convertPublicationYearValue($data['year-publication'], $subCriteria['Tahun Terbit']);
            $data['request-book-value'] = $this->convertRequestBookValue($data['request-book'], $subCriteria['Jumlah Usulan Buku']);
            $data['book-price-value'] = $this->convertPriceValue($data['book-price'], $subCriteria['Harga Buku']);
            $data['library-stock-value'] = $data['library-stock'];
            $data['publisher-stock-value'] = $data['publisher-stock'];
        }

        return $decision;
    }

    public function calculateSquaresSum($normalization, $key)
    {
        $sum = 0;

        foreach ($normalization as $data) {
            $sum += pow($data[$key], 2);
        }

        return $sum;
    }

    public function calculateNormalization($normalization, $key, $sumOfSquares)
    {
        if ($sumOfSquares != 0) {
            $sqrtNumber = sqrt($sumOfSquares);

            foreach ($normalization as &$data) {
                $data["normalization-$key"] = (($data[$key] / $sqrtNumber));
            }
        } else {
            foreach ($normalization as &$data) {
                $data["normalization-$key"] = 0;
            }
        }

        return $normalization;
    }

    public function getNormalizationMatrix($normalization)
    {
        $attributes = [
            'year-publication',
            'request-book',
            'book-price',
            'library-stock',
            'publisher-stock'
        ];


        foreach ($attributes as $attribute) {
            $sumOfSquares = $this->calculateSquaresSum($normalization, "$attribute-value");
            $normalization = $this->calculateNormalization($normalization, "$attribute-value", $sumOfSquares);
        }

        return $normalization;
    }

    public function getOptimizationAttribute($optimization, $criteria)
    {
        $criteria = $criteria->toArray();

        foreach ($optimization as &$data) {
            $data['optimization-year'] = (($data['normalization-year-publication-value'] * $criteria[0]['weight']));
            $data['optimization-request'] = (($data['normalization-request-book-value'] * $criteria[1]['weight']));
            $data['optimization-price'] = (($data['normalization-book-price-value'] * $criteria[2]['weight']));
            $data['optimization-library'] = (($data['normalization-library-stock-value'] * $criteria[3]['weight']));
            $data['optimization-publisher'] = (($data['normalization-publisher-stock-value'] * $criteria[4]['weight']));
        }

        return $optimization;
    }

    public function getRangkingValue($rank, $criteria)
    {
        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        $criteriaNames = ['year', 'request', 'price', 'library', 'publisher',];

        foreach ($rank as &$item) {
            $max = 0;
            $min = 0;

            for ($i = 0; $i < count($criteriaNames); $i++) {
                $criterionName = $criteriaNames[$i];
                $criterionType = $criteria[$i]['type'];

                $criterionValue = $item['optimization-' . $criterionName];

                if ($criterionType === 'Benefit') {
                    $max += $criterionValue;
                } elseif ($criterionType === 'Cost') {
                    $min += $criterionValue;
                }
            }

            $yi = $max - $min;

            $item['maxValue'] = $max;
            $item['minValue'] = $min;
            $item['yiValue'] = $yi;
        }

        usort($rank, function ($a, $b) {
            return $b['yiValue'] <=> $a['yiValue'];
        });

        $ranking = 1;
        foreach ($rank as &$item) {
            $item['rank'] = 'Peringkat ' . $ranking++;
        }

        return $rank;
    }
}
