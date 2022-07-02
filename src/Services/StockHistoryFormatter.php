<?php

namespace App\Services;

class StockHistoryFormatter
{
    public function formatToCandlestickData(array $stockHistory): array
    {
        if (!$stockHistory) {
            return [];
        }

        return array_map(function ($item) {
            return [
                'x' => strtotime($item['date']) * 1000,
                'y' => [
                    number_format((float)$item['open'], 2, '.', ''),
                    number_format((float)$item['high'], 2, '.', ''),
                    number_format((float)$item['low'], 2, '.', ''),
                    number_format((float)$item['close'], 2, '.', ''),
                ]
            ];
        }, $stockHistory);
    }
}
