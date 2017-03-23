<?php

namespace Snelling\Maestro\Console;

use Exception;
use Snelling\Maestro\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create a new folder in the current directory and initializes the application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $filesystem = new Filesystem(getcwd());

        try {
            $filesystem->initBaseDir();
            $filesystem->initConfigFile();
            $filesystem->initScriptsDir();

            $output->writeln('<info>Config file & scripts directory created!</info>');
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }
}