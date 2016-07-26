<?php

namespace WebScraper\Scraper\Result;

use WebScraper\Scraper\Exception\ParseException;

class Value
{
    private $name;
    private $error;
    private $result;

    public function __construct($name, ParseException $error = null, $result = null)
    {
        $this->name = $name;
        $this->error = $error;
        $this->result = $result;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getResult()
    {
        return $this->result;
    }
}
