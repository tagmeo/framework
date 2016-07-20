<?php

namespace Tagmeo\Console\Commands;

use Symfony\Component\Process\Process;
use Tagmeo\Console\Console;
use Tagmeo\Console\Commands\SetupCommand;

class RunVagrantUp
{
    protected $command;

    public function __construct(SetupCommand $command)
    {
        $this->command = $command;
    }

    public function install()
    {
        if (!Console::commandExists('vagrant')) {
            return;
        }

        if (!$this->command->output->confirm('Would you like to provision the Vagrant environment?', true)) {
            return;
        }

        $this->command->output->writeln('<info>Starting Vagrant...</info>');

        $process = (new Process('vagrant up --provision', $this->command->path))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->output->write($line);
        });
    }
}
