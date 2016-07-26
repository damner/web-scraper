<?php

namespace WebScraper\Scraper;

use WebScraper\Scraper\Exception\ParseException;
use WebScraper\Scraper\Exception\RequestException;
use WebScraper\Scraper\Result\Task as ResultTask;
use WebScraper\Scraper\Result\Value as ResultValue;

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

    public function run()
    {
        try {
            $content = $this->request->getResponseContent();
        } catch (RequestException $e) {
            return new ResultTask($e);
        }

        $result = new ResultTask();

        foreach ($this->values as $value) {
            try {
                $result->addValue(new ResultValue($value->getName(), null, $value->parse($content)));
            } catch (ParseException $e) {
                $result->addValue(new ResultValue($value->getName(), $e));
            }
        }

        return $result;
    }
}
