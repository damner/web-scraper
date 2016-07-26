<?php

namespace WebScraper\Config;

use Symfony\Component\CssSelector\Exception\ParseException as CssSelectorParseException;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use WebScraper\Config\Exception\ValidationException;
use WebScraper\Scraper\Request;
use WebScraper\Scraper\Task;
use WebScraper\Scraper\Value;

class Config
{
    private $tasks = [];

    public function addTask(Task $task)
    {
        $this->tasks[] = $task;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getTaskByName($name)
    {
        foreach ($this->tasks as $task) {
            if ($task->getName() === $name) {
                return $task;
            }
        }
    }

    public function initFromArray(array $data)
    {
        $data = $this->validateArray($data);

        $this->tasks = [];

        foreach ($data['tasks'] as $node) {
            $request = new Request($node['request']['url']);

            $task = new Task($node['name'], $request);

            foreach ($node['values'] as $key => $selector) {
                $task->addValue(new Value($key, $selector));
            }

            $this->addTask($task);
        }
    }

    private function validateArray(array $data)
    {
        $processor = new Processor();

        try {
            $data = $processor->processConfiguration(new ConfigConfiguration(), [$data]);
        } catch (InvalidConfigurationException $e) {
            throw new ValidationException($e->getMessage(), null, $e);
        }

        $converter = new CssSelectorConverter();

        foreach ($data['tasks'] as $node) {
            foreach ($node['values'] as $key => $selector) {
                try {
                    $converter->toXPath($selector);
                } catch (CssSelectorParseException $e) {
                    throw new ValidationException($e->getMessage(), null, $e);
                }
            }
        }

        return $data;
    }
}
