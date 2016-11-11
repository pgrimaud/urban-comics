<?php
namespace UrbanComics\tests;

use Goutte\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use UrbanComics\Scraper;

class ScraperTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $fixtures = file_get_contents(__DIR__ . '/fixtures/comics.html');

        $response = new Response(200, [], $fixtures);
        $mock = new MockHandler([$response]);

        $handler = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handler]);

        $this->client = new Client();
        $this->client->setClient($guzzleClient);
    }

    public function testScraperWithInvalidParameters()
    {
        $this->expectException(InvalidArgumentException::class);

        $api = new Scraper($this->client);

        $parameters = [
            'nope' => 'janvier',
            'year' => '2016'
        ];

        $api->getComics($parameters);
    }

    public function testScraper()
    {
        $api = new Scraper($this->client);

        $parameters = [
            'month' => 'janvier',
            'year' => '2012'
        ];

        $comics = $api->getComics($parameters);

        $willReturn[0] = [
            'date' => '20 JANVIER',
            'image' => 'http://www.urban-comics.com/wp-content/uploads/2015/05/watchmen_cover-270x412.jpg',
            'link' => 'http://www.urban-comics.com/watchmen-lintegrale/',
            'title' => 'WATCHMEN –',
            'title_from_link' => 'Watchmen Lintegrale',
            'type' => 'WATCHMEN',
            'authors' => [
                0 => [
                    'position' => 'Scénario',
                    'name' => 'Moore Alan'
                ],
                1 => [
                    'position' => 'Dessin',
                    'name' => 'Gibbons Dave'
                ]
            ]
        ];

        $this->assertSame($willReturn, $comics);
    }
}