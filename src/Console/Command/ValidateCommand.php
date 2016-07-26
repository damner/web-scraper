<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WebScraper\Config\Config;
use WebScraper\Config\Exception\FileNotFoundException;
use WebScraper\Config\Exception\ParseException;
use WebScraper\Config\Exception\ValidationException;

class ValidateCommand extends AbstractCommandWithConfig
{
    protected function configure()
    {
        $this->setName('validate');

        $this->setDescription('Validates config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        try {
            $config = $this->getConfig($input);
        } catch (FileNotFoundException $e) {
            $style->error($e->getMessage());
        } catch (ParseException $e) {
            $style->error($e->getMessage());
        } catch (ValidationException $e) {
            $style->error($e->getMessage());
        }

        if (!isset($config)) {
            return 1;
        }

        $style->success(sprintf('Config file "%s" is valid.', $this->getConfigPath($input)));
    }
}
