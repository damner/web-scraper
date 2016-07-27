<?php

namespace WebScraper\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use WebScraper\Scraper\Task;

class RunCommand extends AbstractCommandWithConfig
{
    protected function configure()
    {
        $this->setName('run');

        $this->setDescription('Run tasks');

        $this->addArgument('task', InputOption::VALUE_OPTIONAL, 'Task name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $values = [];
        foreach ($this->getSpecifiedTasks($input) as $task) {
            $values = array_replace($values, $this->getTaskValues($task));
        }

        $output->writeln(json_encode($values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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

    private function getTaskValues(Task $task)
    {
        $result = $task->getResult();

        if ($result->getError()) {
            return [];
        }

        $values = [];
        foreach ($result->getValues() as $value) {
            if ($value->getError()) {
                continue;
            }

            $key = sprintf('%s:%s', $task->getName(), $value->getName());

            $values[$key] = $value->getResult();
        }

        return $values;
    }
}
