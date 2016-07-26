<?php

namespace WebScraper\Config;

use Symfony\Component\CssSelector\Exception\ParseException as CssSelectorParseException;
use Symfony\Component\CssSelector\CssSelectorConverter;
use RomaricDrigon\MetaYaml\MetaYaml;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;
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

    public function validateArray(array $data)
    {
        $schema = new MetaYaml($this->getSchema(), true);

        try {
            $schema->validate($data);
        } catch (NodeValidatorException $e) {
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
    }

    private function getSchema()
    {
        return [
            'root' => [
                '_type' => 'array',
                '_children' => [
                    'tasks' => [
                        '_type' => 'prototype',
                        '_required' => true,
                        '_prototype' => [
                            '_type' => 'array',
                            '_children' => [
                                'name' => [
                                    '_type' => 'text',
                                    '_required' => true,
                                ],
                                'request' => [
                                    '_type' => 'array',
                                    '_required' => true,
                                    '_children' => [
                                        'url' => [
                                            '_type' => 'text',
                                            '_required' => true,
                                        ],
                                    ],
                                ],
                                'values' => [
                                    '_type' => 'prototype',
                                    '_required' => true,
                                    '_prototype' => [
                                        '_type' => 'text',
                                        '_required' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
