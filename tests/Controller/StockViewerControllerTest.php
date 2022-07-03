<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StockViewerControllerTest extends WebTestCase
{
    public function test_it_can_show_form()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('button', 'Show');
    }

    public function test_it_submit_form_and_show_table_and_graph()
    {
        $toEmail = 'andrew@me.com';

        $client = static::createClient();
        $client->request('GET', '/');

        $client->submitForm('Show', [
            'stock_viewer_form[companySymbol]' => 'AMD',
            'stock_viewer_form[startDate]' => '2022-06-06',
            'stock_viewer_form[endDate]' => '2022-06-06',
            'stock_viewer_form[email]' => $toEmail,
        ]);

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertEmailHeaderSame($email, 'To', $toEmail);

        $this->assertSelectorExists('table:contains("Date")');
    }

    public function test_it_show_validation_errors_when_data_invalid()
    {
        $toEmail = 'andrew-me.com';

        $client = static::createClient();
        $client->request('GET', '/');

        $crawler = $client->submitForm('Show', [
            'stock_viewer_form[companySymbol]' => '',
            'stock_viewer_form[startDate]' => '2022-06-07',
            'stock_viewer_form[endDate]' => '2022-06-06',
            'stock_viewer_form[email]' => $toEmail,
        ]);

        $this->assertEmailCount(0);
        $this->assertSelectorNotExists('table:contains("Date")');

        $this->assertCount(4, $crawler->filter('ul'));
    }
}
