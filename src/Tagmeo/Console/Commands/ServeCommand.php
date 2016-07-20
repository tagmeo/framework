<?php

namespace Tagmeo\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;
use Tagmeo\Foundation\Application;

class ServeCommand extends Command
{
    protected $name = 'serve';
    protected $description = 'Serve the application on the PHP development server';

    protected function configure()
    {
        $this->setName($this->name);

        $this->setDescription($this->description);

        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host address to serve the application on.', 'localhost');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port to serve the application on.', 8000);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basePath = Application::basePath();

        chdir($basePath);

        $host = $input->getOption('host');
        $port = $input->getOption('port');

        $base = ProcessUtils::escapeArgument($basePath);
        $binary = ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));

        $io = new SymfonyStyle($input, $output);

        $io->newLine();
        $io->writeln('<info>Tagmeo development server started on http://'.$host.':'.$port.'/</info>');
        $io->newLine();

        passthru($binary.' -S '.$host.':'.$port.' '.$base.'/server.php');
    }
}
