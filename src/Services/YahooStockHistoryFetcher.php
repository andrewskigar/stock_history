<?php

namespace App\Services;

use App\Contracts\StockHistoryFetcherInterface;
use DateTime;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YahooStockHistoryFetcher implements StockHistoryFetcherInterface
{

    public const EXCEPTION_MESSAGE = 'Stock history api endpoint error or invalid api key.';

    protected const ENDPOINT_URL = 'https://yahoofinance-stocks1.p.rapidapi.com/stock-prices';
    protected const RAPID_API_HOST = 'yahoofinance-stocks1.p.rapidapi.com';

    protected HttpClientInterface $client;

    protected string $rapidApiKey;

    public function __construct(HttpClientInterface $client, string $rapidApiKey)
    {
        $this->client = $client;
        $this->rapidApiKey = $rapidApiKey;
    }

    public function fetchStockHistory(string $companySymbol, DateTime $startDate, DateTime $endDate): array
    {
        $clonedStartDate = clone $startDate;
        $clonedStartDate = $clonedStartDate->modify('-1 days')->format('Y-m-d');
        $endDate = $endDate->format('Y-m-d');

        try {
            $response = $this->client->request('GET', self::ENDPOINT_URL, [
                'query'   => [
                    'StartDateInclusive' => $clonedStartDate,
                    'EndDateInclusive'   => $endDate,
                    'Symbol'             => $companySymbol,
                    'OrderBy'            => 'Ascending',
                ],
                'headers' => [
                    'X-RapidAPI-Host' => self::RAPID_API_HOST,
                    'X-RapidAPI-Key'  => $this->rapidApiKey,
                ],
            ]);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                throw new RuntimeException(static::EXCEPTION_MESSAGE);
            }

            $content = $response->toArray();

            $results = [];
            if (isset($content['results']) && $content['results']) {
                foreach ($content['results'] as $result) {
                    if (date_create($result['date']) <= date_create($endDate)) {
                        $results[] = $result;
                    }
                }
            }

            return $results;
        } catch (TransportExceptionInterface $e) {
            throw new RuntimeException(static::EXCEPTION_MESSAGE);
        }
    }
}
