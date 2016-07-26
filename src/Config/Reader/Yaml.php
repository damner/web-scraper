<?php

namespace WebScraper\Config\Reader;

use Symfony\Component\Yaml\Yaml as YamlParser;
use WebScraper\Config\Config;
use WebScraper\Config\ReaderInterface;
use WebScraper\Scraper\Request;
use WebScraper\Scraper\Task;
use WebScraper\Scraper\Value;
use RuntimeException;

class Yaml implements ReaderInterface
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getConfig()
    {
        if (!is_file($this->path)) {
            throw new RuntimeException(sprintf('Config file "%s" not found.', $this->path));
        }

        $data = YamlParser::parse(file_get_contents($this->path));

        $config = new Config();

        foreach ($data['tasks'] as $node) {
            $request = new Request($node['request']['url']);

            $task = new Task($node['name'], $request);

            if (isset($node['values'])) {
                foreach ($node['values'] as $key => $selector) {
                    $task->addValue(new Value($key, $selector));
                }
            }

            $config->addTask($task);
        }

        return $config;
    }
}
