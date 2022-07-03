<?php

namespace App\Tests\Unit;

use App\Services\StockHistoryFormatter;
use PHPUnit\Framework\TestCase;

class StockHistoryFormatterTest extends TestCase
{

    public function test_it_can_format_data_for_chart(): void
    {
        $stockHistoryFormatter = new StockHistoryFormatter();
        $stockHistoryData = [
            [
                "date" => "2022-06-13",
                "open" => 2135.72998,
                "high" => 2175.830078,
                "low" => 2122.379883,
                "close" => 2127.850098,
                "volume" => 2362600,
                "adjClose" => 2127.850098,
            ]
        ];

        $this->assertEquals([
            [
                "x" =>  strtotime($stockHistoryData[0]['date']) * 1000,
                "y" => [
                    number_format((float)$stockHistoryData[0]['open'], 2, '.', ''),
                    number_format((float)$stockHistoryData[0]['high'], 2, '.', ''),
                    number_format((float)$stockHistoryData[0]['low'], 2, '.', ''),
                    number_format((float)$stockHistoryData[0]['close'], 2, '.', ''),
                ]
            ]
        ], $stockHistoryFormatter->formatToCandlestickData($stockHistoryData));
    }
}
