<?php

namespace Tagmeo\Console\Commands;

use Symfony\Component\Process\Process;
use Tagmeo\Console\Console;
use Tagmeo\Console\Commands\SetupCommand;

class RunVagrantBoxAdd
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

        $vagrantBoxes = shell_exec('vagrant box list');

        if (!preg_match('/ubuntu\/trusty64/', $vagrantBoxes)) {
            if (!$this->command->output->confirm('Would you like to add the Vagrant box?', true)) {
                return;
            }

            $this->command->output->writeln('<info>Adding Vagrant box...</info>');

            $process = (new Process('vagrant box add ubuntu/trusty64', $this->command->path))
                ->setTimeout(null)
                ->setTty($setTty);

            $process->run(function ($type, $line) {
                $this->command->output->write($line);
            });
        }
    }
}
