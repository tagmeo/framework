<?php

namespace Tagmeo\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Tagmeo\Console\Commands\SetupCommand;
use Tagmeo\Foundation\Application;

class GenerateKeys
{
    protected $app;
    protected $file;
    protected $command;

    public function __construct(SetupCommand $command)
    {
        $this->command = $command;
        $this->file = new Filesystem;
    }

    public function install()
    {
        $data = [
            'AUTH_KEY='.$this->generateRandomKey().PHP_EOL,
            'AUTH_SALT='.$this->generateRandomKey().PHP_EOL,
            'NONCE_KEY='.$this->generateRandomKey().PHP_EOL,
            'NONCE_SALT='.$this->generateRandomKey().PHP_EOL,
            'LOGGED_IN_KEY='.$this->generateRandomKey().PHP_EOL,
            'LOGGED_IN_SALT='.$this->generateRandomKey().PHP_EOL,
            'SECURE_AUTH_KEY='.$this->generateRandomKey().PHP_EOL,
            'SECURE_AUTH_SALT='.$this->generateRandomKey().PHP_EOL
        ];

        $this->file->append(Application::environmentFile(), $data);
    }

    protected function generateRandomKey()
    {
        return 'base64:'.base64_encode(random_bytes(32));
    }
}
