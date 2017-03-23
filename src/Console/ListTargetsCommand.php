<?php

namespace Snelling\Maestro\Console;

use Exception;
use Snelling\Maestro\Filesystem;
use Snelling\Maestro\Targets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListTargetsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('targets')
            ->setDescription('List all the targets available');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem(getcwd());

        try {
            $targets = (new Targets($filesystem))->parse();

            $tableRows = [];
            foreach ($targets as $target) {
                $tableRows[] = [
                    $target['name'], $target['user'], $target['ip'],
                ];
            }

            $io = new SymfonyStyle($input, $output);
            $io->table(
                ['Name', 'User', 'IP Address'],
                $tableRows
            );
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}