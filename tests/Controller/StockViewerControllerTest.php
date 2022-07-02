<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockViewerControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Show');
    }

    public function testFormSuccessSubmission()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->submitForm('Show', [
            'stock_viewer_form[companySymbol]' => 'AMD',
            'stock_viewer_form[startDate]' => '2022-06-06',
            'stock_viewer_form[endDate]' => '2022-06-06',
            'stock_viewer_form[email]' => 'andrew@me.com',
        ]);

        $this->assertSelectorExists('table:contains("Date")');
    }
}
