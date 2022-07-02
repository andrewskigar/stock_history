<?php

namespace App\Contracts;

use DateTime;

interface StockHistoryFetcherInterface
{
    public function fetchStockHistory(string $companySymbol, DateTime $startDate, DateTime $endDate): array;
}
