<?php

namespace WebScraper\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use WebScraper\Scraper\Exception\ParseException;

class Value
{
    private $name;
    private $selector;

    public function __construct($name, $selector)
    {
        $this->name = $name;
        $this->selector = $selector;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSelector()
    {
        return $this->selector;
    }

    public function parse($content)
    {
        $crawler = new Crawler($content);

        $nodes = $crawler->filter($this->selector);

        if (count($nodes) === 0) {
            throw new ParseException('Nodes not found.');
        }

        if (count($nodes) > 1) {
            throw new ParseException(sprintf('Found %s nodes.', count($nodes)));
        }

        return $nodes->first()->text();
    }
}
