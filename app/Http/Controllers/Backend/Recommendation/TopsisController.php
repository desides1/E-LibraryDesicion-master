<?php

namespace App\Http\Controllers\Backend\Recommendation;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Criteria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Publisher;

class TopsisController extends Controller
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
        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        foreach ($optimization as &$data) {
            $data['optimization-year'] = (($data['normalization-year-publication-value'] * $criteria[0]['weight']));
            $data['optimization-request'] = (($data['normalization-request-book-value'] * $criteria[1]['weight']));
            $data['optimization-price'] = (($data['normalization-book-price-value'] * $criteria[2]['weight']));
            $data['optimization-library'] = (($data['normalization-library-stock-value'] * $criteria[3]['weight']));
            $data['optimization-publisher'] = (($data['normalization-publisher-stock-value'] * $criteria[4]['weight']));
        }

        return $optimization;
    }

    private function calculateAlternate($rank, $criteria)
    {
        $criteriaNames = ['year', 'request', 'price', 'library', 'publisher'];
        $maxPlusValues = [];
        $minPlusValues = [];
        $maxMinusValues = [];
        $minMinusValues = [];
        $totalDplus = 0;
        $totalDMinus = 0;

        $this->calculateMaxMinValues($rank, $criteria, $criteriaNames, $maxPlusValues, $minPlusValues, $maxMinusValues, $minMinusValues);

        foreach ($rank as &$item) {
            $dplus = [];
            $dminus = [];

            $this->calculateDplusDminus($item, $criteriaNames, $criteria, $maxPlusValues, $minPlusValues, $maxMinusValues, $minMinusValues, $dplus, $dminus);

            $this->calculateTotalDplusTotalDMinus($item, $totalDplus, $totalDMinus, $dplus, $dminus);

            $this->calculateViValue($item, $totalDplus, $totalDMinus);

            unset($item['dplus']);
            unset($item['dminus']);
        }

        usort($rank, function ($a, $b) {
            return $b['viValueTopsis'] <=> $a['viValueTopsis'];
        });

        $this->assignRankings($rank);

        return $rank;
    }

    private function calculateMaxMinValues($rank, $criteria, $criteriaNames, &$maxPlusValues, &$minPlusValues, &$maxMinusValues, &$minMinusValues)
    {
        foreach ($criteriaNames as $criterionName) {
            $criterionType = $criteria[array_search($criterionName, $criteriaNames)]['type'];
            $values = [];

            foreach ($rank as $item) {
                $criterionValue = $item['optimization-' . $criterionName];
                $values[] = $criterionValue;
            }

            if (!empty($values)) {
                if ($criterionType === 'Benefit') {
                    $maxPlusValues[$criterionName] = max($values);
                    $minPlusValues[$criterionName] = min($values);
                } elseif ($criterionType === 'Cost') {
                    $maxMinusValues[$criterionName] = max($values);
                    $minMinusValues[$criterionName] = min($values);
                }
            } else {
                if ($criterionType === 'Benefit') {
                    $maxValues[$criterionName] = 0;
                } elseif ($criterionType === 'Cost') {
                    $minValues[$criterionName] = 0;
                }
            }
        }
    }

    private function calculateDplusDminus(&$item, $criteriaNames, $criteria, $maxPlusValues, $minPlusValues, $maxMinusValues, $minMinusValues, &$dplus, &$dminus)
    {
        foreach ($criteriaNames as $criterionName) {
            $criterionValue = $item['optimization-' . $criterionName];
            $criterionType = $criteria[array_search($criterionName, $criteriaNames)]['type'];

            $valueToNormalize = ($criterionType === 'Benefit') ? [$maxPlusValues[$criterionName], $minPlusValues[$criterionName]] : [$minMinusValues[$criterionName], $maxMinusValues[$criterionName]];

            $item['rank-plus-' . $criterionName . '-value'] = ($valueToNormalize[0] != 0) ? (($valueToNormalize[0])) : 0;
            $item['rank-minus-' . $criterionName . '-value'] = ($valueToNormalize[1] != 0) ? (($valueToNormalize[1])) : 0;

            $dplus[$criterionName] = pow($item['rank-plus-' . $criterionName . '-value'] - $criterionValue, 2);
            $dminus[$criterionName] = pow($criterionValue - $item['rank-minus-' . $criterionName . '-value'], 2);
        }

        $item['dplus'] = $dplus;
        $item['dminus'] = $dminus;
    }

    private function calculateTotalDplusTotalDMinus(&$item, &$totalDplus, &$totalDMinus, $dplus, $dminus)
    {
        $totalDplus = $dplus['year'] + $dplus['request'] + $dminus['price'] + $dminus['library'] + $dplus['publisher'];
        $totalDMinus = $dminus['year'] + $dminus['request'] + $dplus['price'] + $dplus['library'] + $dminus['publisher'];

        $item["d-plus-alternative"] = ((sqrt($totalDplus)));
        $item["d-minus-alternative"] = ((sqrt($totalDMinus)));
    }

    private function calculateViValue(&$item, $totalDplus, $totalDMinus)
    {
        if ($item["d-minus-alternative"] + $item["d-plus-alternative"] != 0) {
            $viValue = ($item["d-minus-alternative"] / ($item["d-minus-alternative"] + $item["d-plus-alternative"]));
        } else {
            $viValue = 0;
        }

        $item["viValueTopsis"] = ($viValue);
    }

    private function assignRankings(&$rank)
    {
        $ranking = 1;
        foreach ($rank as &$item) {
            $item['rank'] = 'Peringkat ' . $ranking++;
        }
    }

    public function getRangkingValue($rank, $criteria)
    {
        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        $data = $this->calculateAlternate($rank, $criteria);

        return $data;
    }
}
