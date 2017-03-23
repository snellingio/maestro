<?php

namespace Snelling\Maestro;

use Symfony\Component\Process\Process;

class Runner
{

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function build($task, $target): Process
    {
        if (in_array($target['ip'], ['local', 'localhost', '127.0.0.1'], true)) {
            return new Process($task['script']);
        }

        return new Process(
            'ssh ' . $target['user'] . '@' . $target['ip'] . ' \'bash -se\' <<TASK-RUNNER' . PHP_EOL
            . $task['script'] . PHP_EOL
            . 'TASK-RUNNER'
        );
    }
}
