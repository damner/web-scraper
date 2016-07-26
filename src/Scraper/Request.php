<?php

namespace WebScraper\Scraper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use WebScraper\Scraper\Exception\RequestException;

class Request
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getResponseContent()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', $this->url);
        } catch (GuzzleRequestException $e) {
            throw new RequestException($e->getMessage(), null, $e);
        }

        return $response->getBody()->getContents();
    }
}
