<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Style\SymfonyStyle;
use WebScraper\Scraper\Task;

class TestCommand extends AbstractCommandWithConfig
{
    protected function configure()
    {
        $this->setName('test');

        $this->setDescription('Tests tasks');

        $this->addArgument('task', InputOption::VALUE_OPTIONAL, 'Task name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        foreach ($this->getSpecifiedTasks($input) as $task) {
            $this->outputTaskResults($style, $task);
        }
    }

    private function getSpecifiedTasks(InputInterface $input)
    {
        $config = $this->getConfig($input);

        $tasks = $config->getTasks();

        $names = $input->getArgument('task');

        if (count($names)) {
            $tasks = [];

            foreach ($names as $name) {
                $task = $config->getTaskByName($name);

                if (!$task) {
                    throw new InvalidArgumentException(sprintf('Task "%s" not found.', $name));
                }

                $tasks[] = $task;
            }
        }

        return $tasks;
    }

    private function outputTaskResults(SymfonyStyle $style, Task $task)
    {
        $style->section($task->getName());
        $style->block($task->getRequest()->getUrl());

        $result = $task->getResult();

        if ($result->getError()) {
            $style->error($result->getError()->getMessage());
        } else {
            $rows = [];
            foreach ($result->getValues() as $value) {
                $error = $value->getError();

                $rows[] = [$value->getName(), $value->getResult(), $error ? $error->getMessage() : ''];
            }

            $style->table(['key', 'value', 'error'], $rows);
        }
    }
}
