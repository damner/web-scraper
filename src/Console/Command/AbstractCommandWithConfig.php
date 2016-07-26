<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use WebScraper\Config\Reader\Yaml as ConfigYamlReader;

abstract class AbstractCommandWithConfig extends Command
{
    protected function getConfig(InputInterface $input)
    {
        $reader = new ConfigYamlReader($input->getOption('config'));

        return $reader->getConfig();
    }
}
