<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use WebScraper\Config\Reader\Yaml as ConfigYamlReader;
use WebScraper\Config\Config;

abstract class AbstractCommandWithConfig extends Command
{
    final protected function getConfigPath(InputInterface $input)
    {
        return $input->getOption('config');
    }

    final protected function getConfigReader(InputInterface $input)
    {
        return new ConfigYamlReader($this->getConfigPath($input));
    }

    final protected function getConfig(InputInterface $input)
    {
        $data = $this->getConfigReader($input)->getArrayFromFile();

        $config = new Config();
        $config->validateArray($data);
        $config->initFromArray($data);

        return $config;
    }
}
