<?php
namespace UrbanComics;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Scraper
{
    /**
     * Scraper constructor.
     * @param Client|null $client
     * @param null $entrypoint
     */
    public function __construct(Client $client = null, $entrypoint = null)
    {
        $this->client = $client ?: new Client();
        $this->entrypoint = $entrypoint ?: 'http://www.urban-comics.com/checklist/';
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function getComics(array $parameters)
    {
        $this->validateParameters($parameters);
        $resource = $this->createResource($parameters);

        $crawler = $this->client->request('GET', $resource);

        $comics = $crawler->filter('.comics-container')->each(function (Crawler $node) {

            $comics = [];

            $date = $node->filter('.date-container');
            $comics['date'] = $date->text();

            $img = $node->filter('.wp-post-image');
            $comics['image'] = $img->attr('data-lazy-src');

            $img = $node->filter('.voir-link');
            $comics['link'] = $img->attr('href');

            // comics title is cut...
            $title = $node->filter('h5');
            $comics['title'] = trim(str_replace('...', '', $title->text()));

            // ...so we get title from link
            $comics['title_from_link'] = $this->titleFromLink($comics['link']);

            $type = $node->filter('.comics-overlay-action a');
            $comics['type'] = $type->attr('data-westory-name');

            $authors = $node->filter('p');
            $comics['authors'] = $this->findAuthors($authors->html());

            return $comics;
        });

        return $comics;
    }

    /**
     * @param array $parameters
     */
    private function validateParameters($parameters)
    {
        foreach (['month', 'year'] as $parameterNeeded) {
            if (!array_key_exists($parameterNeeded, $parameters)) {
                throw new \InvalidArgumentException(sprintf('Parameter "%s" is missing.', $parameterNeeded));
            }
        }
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function createResource($parameters)
    {
        return $this->entrypoint . $parameters['month'] . '/' . $parameters['year'];
    }

    /**
     * @param string $link
     * @return string
     */
    private function titleFromLink($link)
    {
        $urlSplit = explode('/', $link);
        $title = $urlSplit[(count($urlSplit) - 2)];

        return ucwords(str_replace('-', ' ', $title));
    }

    /**
     * @param $node
     * @return array
     */
    private function findAuthors($node)
    {
        $authors = [];

        $lines = explode('<br>', $node);

        foreach ($lines as $line) {
            $fields = explode(':', $line);
            if (count($fields) == 2) {
                $authors[] = [
                    'position' => trim(strip_tags($fields[0])),
                    'name' => trim(strip_tags($fields[1]))
                ];
            }
        }
        return $authors;
    }
}
