<?php

namespace WebScraper\Scraper\Result;

use WebScraper\Scraper\Exception\RequestException;

class Task
{
    private $error;
    private $values = [];

    public function __construct(RequestException $error = null)
    {
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }

    public function addValue(Value $value)
    {
        $this->values[] = $value;
    }

    public function getValues()
    {
        return $this->values;
    }
}
