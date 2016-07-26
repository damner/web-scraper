<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WebScraper\Scraper\Task;

class TasksCommand extends AbstractCommandWithConfig
{
    protected function configure()
    {
        $this->setName('tasks');

        $this->setDescription('Lists tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        foreach ($this->getConfig($input)->getTasks() as $task) {
            $this->outputTask($style, $task);
        }
    }

    private function outputTask(SymfonyStyle $style, Task $task)
    {
        $style->section($task->getName());
        $style->block($task->getRequest()->getUrl());

        $values = $task->getValues();
        if (!count($values)) {
            $style->warning('No values defined.');
        } else {
            $rows = [];
            foreach ($values as $value) {
                $rows[] = [$value->getName(), $value->getSelector()];
            }

            $style->table(['name', 'selector'], $rows);
        }
    }
}
