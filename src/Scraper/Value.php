<?php

namespace WebScraper\Scraper;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\Exception\ParseException as CssSelectorParseException;
use WebScraper\Scraper\Exception\ParseException;
use InvalidArgumentException;

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

        try {
            return $crawler->filter($this->selector)->first()->text();
        } catch (InvalidArgumentException $e) {
            throw new ParseException($e->getMessage(), null, $e);
        } catch (CssSelectorParseException $e) {
            throw new ParseException($e->getMessage(), null, $e);
        }
    }
}
