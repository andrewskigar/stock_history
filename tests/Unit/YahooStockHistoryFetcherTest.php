<?php

namespace App\Tests\Unit;

use App\Services\YahooStockHistoryFetcher;
use DateTime;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class YahooStockHistoryFetcherTest extends TestCase
{

    public function testYahooStockHistoryFetcherWithInvalidRequest(): void
    {
        $client = new MockHttpClient([new MockResponse('invalid', ['http_code' => Response::HTTP_FORBIDDEN])]);
        $stockHistoryFetcher = new YahooStockHistoryFetcher($client, 'invalid');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(YahooStockHistoryFetcher::EXCEPTION_MESSAGE);
        $stockHistoryFetcher->fetchStockHistory('GOOGL', date_create('2022-06-01'), date_create('2022-06-07'));
    }

    /**
     * @dataProvider getStockHistory
     */
    public function testYahooStockHistoryFetcher(array $expectedResult, ResponseInterface $response, string $companySymbol, DateTime $startDate, DateTime $endDate)
    {
        $client = new MockHttpClient([$response]);
        $stockHistoryFetcher = new YahooStockHistoryFetcher($client, 'api_key');
        $result = $stockHistoryFetcher->fetchStockHistory($companySymbol, $startDate, $endDate);
        $this->assertSame($expectedResult, $result);
    }

    public function getStockHistory(): iterable
    {
        $companySymbol = 'GOOGL';

        $startDate = date_create('2022-06-01');
        $endDate = date_create('2022-06-01');

        $response = new MockResponse('{"results": [
            {
                "date":"2022-06-01",
                "open":158.779999,
                "high":160.729996,
                "low":158.330002,
                "close":160.619995,
                "volume":22622100,
                "adjClose":156.924408
            }
        ]}', []);
        yield 'one_row' => [
            [
                [
                    "date" =>"2022-06-01",
                    "open" => 158.779999,
                    "high" => 160.729996,
                    "low" => 158.330002,
                    "close" => 160.619995,
                    "volume" => 22622100,
                    "adjClose" => 156.924408,
                ],
            ], $response, $companySymbol, $startDate, $endDate];

        $startDate = date_create('2022-03-01');
        $endDate = date_create('2022-03-02');

        $response = new MockResponse('{"results": [
            {
                "date":"2022-03-01",
                "open":158.779999,
                "high":160.729996,
                "low":158.330002,
                "close":160.619995,
                "volume":22622100,
                "adjClose":156.924408
            },
            {
                "date":"2022-03-02",
                "open":158.779999,
                "high":160.729996,
                "low":158.330002,
                "close":160.619995,
                "volume":22622100,
                "adjClose":156.924408
            }
        ]}', []);
        yield 'more_then_one' => [
            [
                [
                    "date" =>"2022-03-01",
                    "open" => 158.779999,
                    "high" => 160.729996,
                    "low" => 158.330002,
                    "close" => 160.619995,
                    "volume" => 22622100,
                    "adjClose" => 156.924408,
                ],
                [
                    "date" =>"2022-03-02",
                    "open" => 158.779999,
                    "high" => 160.729996,
                    "low" => 158.330002,
                    "close" => 160.619995,
                    "volume" => 22622100,
                    "adjClose" => 156.924408,
                ],
            ], $response, $companySymbol, $startDate, $endDate];

        $startDate = date_create('2022-01-01');
        $endDate = date_create('2022-01-01');

        $response = new MockResponse('{"results": []}', []);
        yield 'zero' => [[], $response, $companySymbol, $startDate, $endDate];
    }
}
