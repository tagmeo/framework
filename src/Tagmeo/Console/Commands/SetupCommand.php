<?php

namespace Tagmeo\Console\Commands;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class SetupCommand extends SymfonyCommand
{
    public $path;
    public $input;
    public $output;

    protected function configure()
    {
        $this
            ->setName('setup')
            ->setDescription('Setup your Tagmeo application');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $this->path = realpath(null);

        $installers = [
            UpdateEnvFile::class,
            GenerateKeys::class,
            RunNpmInstall::class,
            RunGulp::class,
            RunVagrantBoxAdd::class,
            RunVagrantPluginInstall::class,
            RunVagrantUp::class
        ];

        foreach ($installers as $installer) {
            (new $installer($this))->install();
        }
    }
}
