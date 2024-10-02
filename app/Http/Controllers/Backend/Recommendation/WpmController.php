<?php

namespace App\Http\Controllers\Backend\Recommendation;

use App\Models\Book;
use App\Models\Criteria;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WpmController extends Controller
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

    private function normalizeValue($value, $weight)
    {
        return pow($value, $weight);
    }

    private function normalizeValueWithCondition($value, $weight)
    {
        return ($value > 0) ? pow($value, $weight) : 0;
    }

    public function getNormalizationMatrix($normalization, $criteria)
    {
        $criteriaArray = $criteria->toArray();
        $weights = [
            'yearPublication' => $criteriaArray[0]['weight'],
            'requestBook' => $criteriaArray[1]['weight'],
            'bookPrice' => $criteriaArray[2]['weight'],
            'libraryStock' => $criteriaArray[3]['weight'],
            'publisherStock' => $criteriaArray[4]['weight'],
        ];

        foreach ($normalization as &$data) {
            $data['normalization-year-publication-value'] = $this->normalizeValue($data['year-publication-value'], $weights['yearPublication']);
            $data['normalization-request-book-value'] = $this->normalizeValue($data['request-book-value'], $weights['requestBook']);
            $data['normalization-book-price-value'] = $this->normalizeValue($data['book-price-value'], -$weights['bookPrice']);
            $data['normalization-library-stock-value'] = $this->normalizeValueWithCondition($data['library-stock-value'], -$weights['libraryStock']);
            $data['normalization-publisher-stock-value'] = $this->normalizeValueWithCondition($data['publisher-stock-value'], $weights['publisherStock']);
        }

        return $normalization;
    }

    private function calculateOptimizationValue($optimization)
    {
        return $optimization['normalization-year-publication-value']
            * $optimization['normalization-request-book-value']
            * $optimization['normalization-book-price-value']
            * $optimization['normalization-library-stock-value']
            * $optimization['normalization-publisher-stock-value'];
    }

    private function calculateOptimizationResult($optimization)
    {
        return $optimization['normalization-year-publication-value']
            + $optimization['normalization-request-book-value']
            + $optimization['normalization-book-price-value']
            + $optimization['normalization-library-stock-value']
            + $optimization['normalization-publisher-stock-value'];
    }

    public function getOptimizationAttribute($optimization)
    {
        foreach ($optimization as &$data) {
            $data['optimization-value'] = $this->calculateOptimizationValue($data);
            $data['optimization-result'] = $this->calculateOptimizationResult($data);
        }

        return $optimization;
    }

    public function getRangkingValue($rank)
    {
        $criteriaNames = ['value'];
        $dataValueResult = 0;

        foreach ($rank as $item) {
            foreach ($criteriaNames as $criterionName) {
                $criterionValue = $item['optimization-' . $criterionName];
                $dataValueResult += $criterionValue;
            }
        }

        foreach ($rank as &$item) {
            $criterionValue = $item['optimization-value'];
            $dataValue = $dataValueResult != 0 ? $criterionValue / $dataValueResult : 0;
            $viValue = $dataValue;
            $item['viValue'] = $viValue;
        }
        usort($rank, function ($a, $b) {
            return $b['viValue'] <=> $a['viValue'];
        });

        $ranking = 1;
        foreach ($rank as &$item) {
            $item['rank'] = 'Peringkat ' . $ranking++;
        }

        return $rank;
    }
}
