<?php

namespace WebScraper\Config\Reader;

use Symfony\Component\Yaml\Yaml as YamlParser;
use Symfony\Component\Yaml\Exception\ParseException as YamlParseException;
use WebScraper\Config\ReaderInterface;
use WebScraper\Config\Exception\FileNotFoundException;
use WebScraper\Config\Exception\ParseException;

class Yaml implements ReaderInterface
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getArrayFromFile()
    {
        if (!is_file($this->path)) {
            throw new FileNotFoundException(sprintf('Config file "%s" not found.', $this->path));
        }

        try {
            $data = (array) YamlParser::parse(file_get_contents($this->path));
        } catch (YamlParseException $e) {
            throw new ParseException($e->getMessage(), null, $e);
        }

        return $data;
    }
}
