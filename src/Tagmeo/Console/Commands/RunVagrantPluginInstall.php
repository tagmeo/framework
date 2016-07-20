<?php

namespace Tagmeo\Console\Commands;

use Symfony\Component\Process\Process;
use Tagmeo\Console\Commands\SetupCommand;

class RunVagrantPluginInstall
{
    protected $command;

    public function __construct(SetupCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        if (!$this->command->output->confirm('Would you like to install the Vagrant plugins?', true)) {
            return;
        }

        $this->command->output->writeln('<info>Installing Vagrant plugins...</info>');

        $process = (new Process(
            'vagrant plugin install vagrant-auto_network vagrant-cachier vagrant-hostmanager',
            $this->command->path
        ))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
