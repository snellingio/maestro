<?php

namespace Snelling\Maestro\Console;

use Exception;
use Snelling\Maestro\Filesystem;
use Snelling\Maestro\Runner;
use Snelling\Maestro\Scripts;
use Snelling\Maestro\Targets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class RunScriptOnTargetCommand extends Command
{

    private $output;

    private $target;

    protected function configure()
    {
        $this
            ->setName('run')
            ->addArgument('script', InputArgument::REQUIRED, 'Name of the script you want to run')
            ->addArgument('target', InputArgument::REQUIRED, 'Name of the target you want to run the script on');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem(getcwd());

        try {
            $script = (new Scripts($filesystem))->getByName($input->getArgument('script'));
            $target = (new Targets($filesystem))->getByName($input->getArgument('target'));

            $output->writeln('<info>Running script ' . $script['name'] . ' on ' . $target['ip'] . '</info>');

            $runner  = new Runner($filesystem);
            $process = $runner->build($script, $target);

            $this->target = $target;
            $this->output = $output;

            $process->run(function ($type, $buffer) {
                $lines = explode("\n", $buffer);
                foreach ($lines as $line) {
                    if (strlen(trim($line)) === 0) {
                        continue;
                    }
                    if ($type === Process::OUT) {
                        $this->output->writeln('<comment>[' . $this->target['ip'] . ']</comment>: ' . trim($line));
                    } else {
                        $this->output->writeln('<comment>[' . $this->target['ip'] . ']</comment>: <error>' . trim($line) . '</error>');
                    }
                }
            });

        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}