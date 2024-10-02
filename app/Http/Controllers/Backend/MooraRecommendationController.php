<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MooraRecommendationController extends Controller
{
    public function alternativeAtribute($alternative)
    {
        $mergedData = [];

        foreach ($alternative as $decision) {
            $idToCheck = $decision->publisher_id;

            if (empty($mergedData[$idToCheck])) {
                $mergedData[$idToCheck] = $this->initializeMergedData($decision);
            }

            if (!empty($decision->publisher_id)) {
                $this->mergeBookData($mergedData[$idToCheck], $decision->publisher);
            }

            $mergedData[$idToCheck]['request-book'] += 1;
        }

        return $mergedData;
    }

    public function mergeBookData(&$mergedData, $book)
    {
        $mergedData['title'] = $book->title;
        $mergedData['year-publication'] = Carbon::createFromFormat('Y-m-d', $book->publication_date)->format('Y');
        $mergedData['book-price'] = $book->price;
        $mergedData['publisher_id'] = $book->id;

        $bookQuery = Publisher::where('id', '!=', $book->id)->where('isbn', $book->isbn)->first();

        $mergedData['library-stock'] = ($book->user_id == 37) ? $book->available_stock : ($book->isbn && $bookQuery ? $bookQuery->available_stock : 0);
        $mergedData['publisher-stock'] = ($book->user_id == 37) ? ($book->isbn && $bookQuery ? $bookQuery->available_stock : 0) : $book->available_stock;
    }

    public function convertPublicationYearToValue($publicationYear)
    {
        switch (true) {
            case ($publicationYear > 2022):
                return 1.0;
            case ($publicationYear >= 2018 && $publicationYear <= 2022):
                return 0.8;
            case ($publicationYear >= 2013 && $publicationYear <= 2017):
                return 0.6;
            case ($publicationYear >= 2008 && $publicationYear <= 2012):
                return 0.4;
            case ($publicationYear < 2008):
                return 0.2;
            default:
                return 0.0;
        }
    }

    public function convertRequestBookValue($requestBook)
    {
        switch (true) {
            case ($requestBook > 15):
                return 1.0;
            case ($requestBook >= 12 && $requestBook <= 15):
                return 0.8;
            case ($requestBook >= 9 && $requestBook <= 11):
                return 0.6;
            case ($requestBook >= 5 && $requestBook <= 8):
                return 0.4;
            case ($requestBook >= 1 && $requestBook <= 4):
                return 0.2;
            default:
                return 0.0;
        }
    }

    public function convertPriceBookValue($priceBook)
    {
        switch (true) {
            case ($priceBook >= 1 && $priceBook <= 100000):
                return 1.0;
            case ($priceBook >= 101000 && $priceBook <= 200000):
                return 0.8;
            case ($priceBook >= 201000 && $priceBook <= 300000):
                return 0.6;
            case ($priceBook >= 301000 && $priceBook <= 400000):
                return 0.4;
            case ($priceBook > 400000):
                return 0.2;
            default:
                return 0.0;
        }
    }

    public function convertStockLibraryValue($stockLibrary)
    {
        switch (true) {
            case ($stockLibrary >= 0 && $stockLibrary <= 4):
                return 1.0;
            case ($stockLibrary >= 5 && $stockLibrary <= 8):
                return 0.8;
            case ($stockLibrary >= 9 && $stockLibrary <= 12):
                return 0.6;
            case ($stockLibrary >= 13 && $stockLibrary <= 16):
                return 0.4;
            case ($stockLibrary > 16):
                return 0.2;
            default:
                return 0.0;
        }
    }

    public function convertStockPublisherValue($stockPublisher)
    {
        switch (true) {
            case $stockPublisher > 16:
                return 1.0;
            case $stockPublisher >= 13 && $stockPublisher <= 16:
                return 0.8;
            case $stockPublisher >= 9 && $stockPublisher <= 12:
                return 0.6;
            case $stockPublisher >= 5 && $stockPublisher <= 8:
                return 0.4;
            case $stockPublisher >= 0 && $stockPublisher <= 4:
                return 0.2;
            default:
                return 0.0;
        }
    }

    public function decisionMatrix($mergedData)
    {
        foreach ($mergedData as &$data) {
            $data['year-publication-value'] =  $this->convertPublicationYearToValue($data['year-publication']);
            $data['request-book-value'] = $this->convertRequestBookValue($data['request-book']);
            $data['book-price-value'] = $this->convertPriceBookValue($data['book-price']);
            $data['library-stock-value'] = $this->convertStockLibraryValue($data['library-stock']);
            $data['publisher-stock-value'] = $this->convertStockPublisherValue($data['publisher-stock']);
        }
        return $mergedData;
    }

    public function calculateSquaresSum($decision, $key)
    {
        $sum = 0;

        foreach ($decision as $data) {
            $sum += pow($data[$key], 2);
        }

        return $sum;
    }

    public function calculateNormalization($decision, $key, $sumOfSquares)
    {
        if ($sumOfSquares != 0) {
            $sqrtNumber = sqrt($sumOfSquares);

            foreach ($decision as &$data) {
                $data["normalization-$key"] = number_format(round($data[$key] / $sqrtNumber, 3), 3);
            }
        } else {
            foreach ($decision as &$data) {
                $data["normalization-$key"] = 0;
            }
        }

        return $decision;
    }

    public function normalizationMatrix($decision)
    {
        $attributes = [
            'year-publication',
            'request-book',
            'book-price',
            'library-stock',
            'publisher-stock'
        ];

        foreach ($attributes as $attribute) {
            $sumOfSquares = $this->calculateSquaresSum($decision, "$attribute-value");
            $decision = $this->calculateNormalization($decision, "$attribute-value", $sumOfSquares);
        }

        return $decision;
    }

    public function initializeMergedData($decision)
    {
        return [
            'title' => '',
            'year-publication' => null,
            'book-price' => 0,
            'library-stock' => 0,
            'publisher-stock' => 0,
            'request-book' => 0,
        ];
    }

    public function optimizationValue($normalization)
    {
        foreach ($normalization as &$item) {
            // // calculate respondent
            // $item['optimization-year'] = number_format(round($item['normalization-year-publication-value'] * 0.20, 3), 3);
            // $item['optimization-request'] = number_format(round($item['normalization-request-book-value'] * 0.20, 3), 3);
            // $item['optimization-price'] = number_format(round($item['normalization-book-price-value'] * 0.10, 3), 3);
            // $item['optimization-library'] = number_format(round($item['normalization-library-stock-value'] * 0.30, 3), 3);
            // $item['optimization-publisher'] = number_format(round($item['normalization-publisher-stock-value'] * 0.20, 3), 3);
            // calculate system
            $item['optimization-year'] = number_format(round($item['normalization-year-publication-value'] * 0.20, 3), 3);
            $item['optimization-request'] = number_format(round($item['normalization-request-book-value'] * 0.20, 3), 3);
            $item['optimization-price'] = number_format(round($item['normalization-book-price-value'] * 0.10, 3), 3);
            $item['optimization-library'] = number_format(round($item['normalization-library-stock-value'] * 0.25, 3), 3);
            $item['optimization-publisher'] = number_format(round($item['normalization-publisher-stock-value'] * 0.25, 3), 3);
        }

        return $normalization;
    }

    public function rangkingValue($optimization, $budgetPrice)
    {
        $totalPrice = 0;

        foreach ($optimization as &$item) {
            $max = $item['optimization-year'] + $item['optimization-request'];
            $min = $item['optimization-price'] + $item['optimization-library'] + $item['optimization-publisher'];
            $yi = $max - $min;

            $item['maxValue'] = $max;
            $item['minValue'] = $min;
            $item['yiValue'] = $yi;
        }

        usort($optimization, function ($a, $b) {
            return $b['yiValue'] <=> $a['yiValue'];
        });

        $rank = 1;
        foreach ($optimization as &$item) {
            $item['rank'] = 'Peringkat ' . $rank++;
            $totalPrice += $item['book-price'];

            if ($totalPrice <= $budgetPrice) {
                $item['status'] = 'Rekomendasi Sesuai Anggaran';
            } else {
                $item['status'] = 'Tidak Direkomendasikan Sesuai Anggaran';
            }
            $item['budget_total'] = $totalPrice;
        }

        return $optimization;
    }

    public function rankRecommended($optimization, $budgetPrice)
    {
        $totalPrice = 0;
        $recommendedItems = [];

        foreach ($optimization as &$item) {
            $max = $item['optimization-year'] + $item['optimization-request'];
            $min = $item['optimization-price'] + $item['optimization-library'] + $item['optimization-publisher'];
            $yi = $max - $min;

            $item['maxValue'] = $max;
            $item['minValue'] = $min;
            $item['yiValue'] = $yi;
        }

        usort($optimization, function ($a, $b) {
            return $b['yiValue'] <=> $a['yiValue'];
        });

        $rank = 1;
        foreach ($optimization as &$item) {
            $item['rank'] = 'Peringkat ' . $rank++;
            $totalPrice += $item['book-price'];

            if ($totalPrice <= $budgetPrice) {
                $item['status'] = 'Rekomendasi Sesuai Anggaran';
                $recommendedItems[] = $item;
            } else {
                $item['status'] = 'Tidak Direkomendasikan Sesuai Anggaran';
            }
            $item['budget_total'] = $totalPrice;
        }

        return $recommendedItems;
    }
}
