<?php

namespace WebScraper\Scraper;

use WebScraper\Scraper\Exception\RequestException;
use WebScraper\Scraper\Result\Task as Result;

class Task
{
    private $name;
    private $request;
    private $values = [];

    public function __construct($name, Request $request)
    {
        $this->name = $name;
        $this->request = $request;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addValue(Value $value)
    {
        $this->values[] = $value;
    }

    public function getValues()
    {
        return $this->values;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResult()
    {
        try {
            $content = $this->request->getResponseContent();
        } catch (RequestException $e) {
            return new Result($e);
        }

        $result = new Result();

        foreach ($this->values as $value) {
            $result->addValue($value->getResult($content));
        }

        return $result;
    }
}
