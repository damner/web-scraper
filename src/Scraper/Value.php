<?php

namespace WebScraper\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use WebScraper\Scraper\Exception\ParseException;
use WebScraper\Scraper\Result\Value as Result;

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

    public function getResult($content)
    {
        try {
            $result = $this->parse($content);
        } catch (ParseException $e) {
            return new Result($this->getName(), $e);
        }

        return new Result($this->getName(), null, $result);
    }

    private function parse($content)
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
