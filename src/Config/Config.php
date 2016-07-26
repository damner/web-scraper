<?php

namespace WebScraper\Config;

use WebScraper\Scraper\Task;

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
}
