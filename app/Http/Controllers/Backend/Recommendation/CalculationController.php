<?php

namespace App\Http\Controllers\Backend\Recommendation;

use App\Models\Book;
use App\Models\Criteria;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculationController extends Controller
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

    public function calculateSquaresSumMoora($normalization, $key)
    {
        $sum = 0;

        foreach ($normalization as $data) {
            $sum += pow($data[$key], 2);
        }

        return $sum;
    }

    public function calculateNormalizationMoora($normalization, $key, $sumOfSquares)
    {
        if ($sumOfSquares != 0) {
            $sqrtNumber = sqrt($sumOfSquares);

            foreach ($normalization as &$data) {
                $data["normalization-moora-$key"] = (($data[$key] / $sqrtNumber));
                $data["normalization-topsis-$key"] = (($data[$key] / $sqrtNumber));
            }
        } else {
            foreach ($normalization as &$data) {
                $data["normalization-moora-$key"] = 0;
                $data["normalization-topsis-$key"] = 0;
            }
        }

        return $normalization;
    }

    public function calculateNormalizationSaw($normalization, $criteria)
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

                $item['normalization-saw-' . $criterionName . '-value'] = $normalizedValue;
            }
        }

        return $normalization;
    }

    private function normalizeValueWpm($value, $weight)
    {
        return pow($value, $weight);
    }

    private function normalizeValueWithConditionWpm($value, $weight)
    {
        return ($value > 0) ? pow($value, $weight) : 0;
    }

    public function getNormalizationMatrix($normalization, $criteria)
    {
        $attributes = [
            'year-publication',
            'request-book',
            'book-price',
            'library-stock',
            'publisher-stock'
        ];

        $criteriaArray = $criteria->toArray();
        $weights = [
            'yearPublication' => $criteriaArray[0]['weight'],
            'requestBook' => $criteriaArray[1]['weight'],
            'bookPrice' => $criteriaArray[2]['weight'],
            'libraryStock' => $criteriaArray[3]['weight'],
            'publisherStock' => $criteriaArray[4]['weight'],
        ];

        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        foreach ($attributes as &$attribute) {
            $sumOfSquares = $this->calculateSquaresSumMoora($normalization, "$attribute-value");
            $normalization = $this->calculateNormalizationMoora($normalization, "$attribute-value", $sumOfSquares);
            $normalization = $this->calculateNormalizationSaw($normalization, $criteria);
        }

        foreach ($normalization as &$data) {
            $data['normalization-wpm-year-publication-value'] = $this->normalizeValueWpm($data['year-publication-value'], $weights['yearPublication']);
            $data['normalization-wpm-request-book-value'] = $this->normalizeValueWpm($data['request-book-value'], $weights['requestBook']);
            $data['normalization-wpm-book-price-value'] = $this->normalizeValueWpm($data['book-price-value'], -$weights['bookPrice']);
            $data['normalization-wpm-library-stock-value'] = $this->normalizeValueWithConditionWpm($data['library-stock-value'], -$weights['libraryStock']);
            $data['normalization-wpm-publisher-stock-value'] = $this->normalizeValueWithConditionWpm($data['publisher-stock-value'], $weights['publisherStock']);
        }

        return $normalization;
    }

    private function calculateOptimizationValue($optimization)
    {
        return $optimization['normalization-wpm-year-publication-value']
            * $optimization['normalization-wpm-request-book-value']
            * $optimization['normalization-wpm-book-price-value']
            * $optimization['normalization-wpm-library-stock-value']
            * $optimization['normalization-wpm-publisher-stock-value'];
    }

    private function calculateOptimizationResult($optimization)
    {
        return $optimization['normalization-wpm-year-publication-value']
            + $optimization['normalization-wpm-request-book-value']
            + $optimization['normalization-wpm-book-price-value']
            + $optimization['normalization-wpm-library-stock-value']
            + $optimization['normalization-wpm-publisher-stock-value'];
    }

    public function getOptimizationAttribute($optimization, $criteria)
    {
        $criteria = $criteria->toArray();

        foreach ($optimization as &$data) {
            $data['optimization-moora-year'] = (($data['normalization-moora-year-publication-value'] * $criteria[0]['weight']));
            $data['optimization-moora-request'] = (($data['normalization-moora-request-book-value'] * $criteria[1]['weight']));
            $data['optimization-moora-price'] = (($data['normalization-moora-book-price-value'] * $criteria[2]['weight']));
            $data['optimization-moora-library'] = (($data['normalization-moora-library-stock-value'] * $criteria[3]['weight']));
            $data['optimization-moora-publisher'] = (($data['normalization-moora-publisher-stock-value'] * $criteria[4]['weight']));
            $data['optimization-saw-year'] = (($data['normalization-saw-year-publication-value'] * $criteria[0]['weight']));
            $data['optimization-saw-request'] = (($data['normalization-saw-request-book-value'] * $criteria[1]['weight']));
            $data['optimization-saw-price'] = (($data['normalization-saw-book-price-value'] * $criteria[2]['weight']));
            $data['optimization-saw-library'] = (($data['normalization-saw-library-stock-value'] * $criteria[3]['weight']));
            $data['optimization-saw-publisher'] = (($data['normalization-saw-publisher-stock-value'] * $criteria[4]['weight']));
            $data['optimization-topsis-year'] = (($data['normalization-topsis-year-publication-value'] * $criteria[0]['weight']));
            $data['optimization-topsis-request'] = (($data['normalization-topsis-request-book-value'] * $criteria[1]['weight']));
            $data['optimization-topsis-price'] = (($data['normalization-topsis-book-price-value'] * $criteria[2]['weight']));
            $data['optimization-topsis-library'] = (($data['normalization-topsis-library-stock-value'] * $criteria[3]['weight']));
            $data['optimization-topsis-publisher'] = (($data['normalization-topsis-publisher-stock-value'] * $criteria[4]['weight']));
            $data['optimization-wpm-value'] = $this->calculateOptimizationValue($data);
            $data['optimization-wpm-result'] = $this->calculateOptimizationResult($data);
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

        return $rank;
    }

    private function calculateMaxMinValues($rank, $criteria, $criteriaNames, &$maxPlusValues, &$minPlusValues, &$maxMinusValues, &$minMinusValues)
    {
        foreach ($criteriaNames as $criterionName) {
            $criterionType = $criteria[array_search($criterionName, $criteriaNames)]['type'];
            $values = [];

            foreach ($rank as $item) {
                $criterionValue = $item['optimization-topsis-' . $criterionName];
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
            $criterionValue = $item['optimization-topsis-' . $criterionName];
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

        $item["viValue-topsis"] = ($viValue);
    }

    public function getRangkingValue($rank, $criteria, $selectedCalculate)
    {
        if (is_object($criteria)) {
            $criteria = $criteria->toArray();
        }

        $criteriaNames = ['year', 'request', 'price', 'library', 'publisher',];
        $dataValueResult = 0;

        foreach ($rank as &$item) {
            $max = 0;
            $min = 0;

            for ($i = 0; $i < count($criteriaNames); $i++) {
                $criterionName = $criteriaNames[$i];
                $criterionType = $criteria[$i]['type'];

                $criterionValue = $item['optimization-moora-' . $criterionName];

                if ($criterionType === 'Benefit') {
                    $max += $criterionValue;
                } elseif ($criterionType === 'Cost') {
                    $min += $criterionValue;
                }
            }

            $dataValue = 0;

            foreach ($criteriaNames as $criterionName) {
                $criterionValue = $item['optimization-saw-' . $criterionName];
                $dataValue += $criterionValue;
            }

            $criterionValue = $item['optimization-wpm-value'];
            $dataValueWpm = $dataValueResult != 0 ? $criterionValue / $dataValueResult : 0;
            $viValueWpm = $dataValueWpm;
            $item['viValue-wpm'] = $viValueWpm;

            $viValue = (($dataValue));
            $item['viValue-saw'] = $viValue;

            $yi = $max - $min;

            $item['maxValue'] = $max;
            $item['minValue'] = $min;
            $item['yiValue-moora'] = $yi;
        }

        $rank = $this->calculateAlternate($rank, $criteria);

        $mapping = [
            'calmoora' => 'yiValue-moora',
            'calsaw' => 'viValue-saw',
            'calwpm' => 'viValue-wpm',
            'caltopsis' => 'viValue-topsis'
        ];

        $values = array_intersect_key($mapping, array_flip($selectedCalculate));

        foreach ($rank as &$item) {
            $total = 0;
            $count = 0;

            foreach ($values as $value) {
                if (isset($item[$value])) {
                    $total += $item[$value];
                    $count++;
                }
            }

            $item['averageTotal'] = $count > 0 ? $total / count($values) : 0;
        }

        usort($rank, function ($a, $b) {
            return $b['averageTotal'] <=> $a['averageTotal'];
        });

        $ranking = 1;
        foreach ($rank as &$item) {
            $item['rank'] = 'Peringkat ' . $ranking++;
        }

        return $rank;
    }
}
