<?php

namespace Snelling\Maestro\Console;

use Exception;
use Snelling\Maestro\Filesystem;
use Snelling\Maestro\Scripts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListScriptsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('scripts')
            ->setDescription('List all the scripts available to run');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filesystem = new Filesystem(getcwd());

        try {
            $tasks = (new Scripts($filesystem))->parse();

            $tableRows = [];
            foreach ($tasks as $task) {
                $tableRows[] = [
                    $task['name'], $task['description'],
                ];
            }

            $io = new SymfonyStyle($input, $output);
            $io->table(
                ['Name', 'Description'],
                $tableRows
            );
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}