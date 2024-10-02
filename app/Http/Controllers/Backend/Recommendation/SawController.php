<?php

namespace App\Http\Controllers\Backend\Recommendation;

use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Publisher;

class SawController extends Controller
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

    public function calculateNormalization($normalization, $criteria)
    {
        $criteriaNames = ['year-publication', 'request-book', 'book-price', 'library-stock', 'publisher-stock'];

        $maxValues = [];
        $minValues = [];

        foreach ($criteriaNames as $criterionName) {
            $criterionType = $criteria[array_search($criterionName, $criteriaNames)]['type'];
            $values = [];

            foreach ($normalization as $item) {
                $criterionValue = $item[$criterionName . '-value'];
                $values[] = $criterionValue;
            }

            if (!empty($values)) {
                if ($criterionType === 'Benefit') {
                    $maxValues[$criterionName] = max($values);
                } elseif ($criterionType === 'Cost') {
                    $minValues[$criterionName] = min($values);
                }
            } else {
                if ($criterionType === 'Benefit') {
                    $maxValues[$criterionName] = 0;
                } elseif ($criterionType === 'Cost') {
                    $minValues[$criterionName] = 0;
                }
            }
        }

        foreach ($normalization as &$item) {
            foreach ($criteriaNames as $criterionName) {
                $criterionValue = $item[$criterionName . '-value'];
                $criterionType = $criteria[array_search($criterionName, $criteriaNames)]['type'];

                $valueToNormalize = ($criterionType === 'Benefit') ? $maxValues[$criterionName] : $minValues[$criterionName];
                $normalizedValue = ($valueToNormalize != 0) ? (($criterionValue / $valueToNormalize)) : 0;

                $item['normalization-' . $criterionName . '-value'] = $normalizedValue;
            }
        }

        return $normalization;
    }

    public function getNormalizationMatrix($normalization, $criteria)
    {
        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        $data = $this->calculateNormalization($normalization, $criteria);

        return $data;
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

    public function getRangkingValue($rank)
    {
        $criteriaNames = ['year', 'request', 'price', 'library', 'publisher'];

        foreach ($rank as &$item) {
            $dataValue = 0;
            foreach ($criteriaNames as $criterionName) {
                $criterionValue = $item['optimization-' . $criterionName];
                $dataValue += $criterionValue;
            }
            $viValue = (($dataValue));
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
