<?php

namespace Tagmeo\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Tagmeo\Console\Commands\SetupCommand;
use Tagmeo\Foundation\Application;

class UpdateEnvFile
{
    protected $file;
    protected $command;

    public function __construct(SetupCommand $command)
    {
        $this->command = $command;
        $this->file = new Filesystem;
    }

    public function install()
    {
        $env = $this->command->output->choice('What is the application environment?', ['development', 'staging', 'production'], 'development');

        $siteUrl = $this->command->output->ask('What is the website address?', 'http://tagmeo.dev', function ($url) {
            $url = rtrim(preg_replace('/^(https?:\/\/)(.*)$/', '$1$2', $url), '/');

            if (preg_match('/^https?:\/\//', $url)) {
                return $url;
            }

            return 'http://'.$url;
        });

        $dbHost = $this->command->output->ask('What is the database host?', 'localhost');
        $dbName = $this->command->output->ask('What is the database name?', 'tagmeo');
        $dbUser = $this->command->output->ask('What is the database user?', 'root');
        $dbPass = $this->command->output->askHidden('What is the database user password?');
        $dbPrefix = $this->command->output->ask('What is the database table prefix?', 'wp_');
        $dbCharset = $this->command->output->ask('What is the database character set?', 'utf8');
        $dbCollation = $this->command->output->ask('What is the database collation?', 'utf8_unicode_ci');

        $disableUpdater = $this->command->output->choice('Disable the automatic updater?', ['yes', 'no'], 'yes');
        $disableCronjob = $this->command->output->choice('Disable the WordPress cronjob?', ['yes', 'no'], 'yes');
        $disableFileEditing = $this->command->output->choice('Disable file editing in the backend?', ['yes', 'no'], 'yes');

        $envFile = Application::environmentFile();

        if ($this->file->exists($envFile)) {
            $this->command->output->warning('You are about to overwrite the existing .env file!');

            if (!$this->command->output->confirm('Would you like to continue?', true)) {
                return;
            }
        }

        $vagrantFile = Application::basePath('Vagrantfile');

        if ($this->file->exists($vagrantFile)) {
            $data = $this->file->get($vagrantFile);

            $data = preg_replace(
                '/mysql_root_password = "(.*)"/',
                'mysql_root_password = "'.$dbPass.'"',
                $data
            );

            $fqdn = preg_replace('/^(https?:\/\/)(.*)$/', '$2', $siteUrl);

            $data = preg_replace(
                '/hostname = "(.*)"/',
                'hostname = "'.$fqdn.'"',
                $data
            );

            $this->file->put($vagrantFile, $data);
        }

        $disableUpdater = ($disableUpdater === 'yes') ? 'true' : 'false';
        $disableCronjob = ($disableCronjob === 'yes') ? 'true' : 'false';
        $disableFileEditing = ($disableFileEditing === 'yes') ? 'true' : 'false';

        $data = [
            'WP_ENV='.$env.PHP_EOL,
            'WP_HOME='.$siteUrl.PHP_EOL,
            'WP_SITEURL='.$siteUrl.'/cms'.PHP_EOL,
            'DB_HOST='.$dbHost.PHP_EOL,
            'DB_NAME='.$dbName.PHP_EOL,
            'DB_USER='.$dbUser.PHP_EOL,
            'DB_PASS='.$dbPass.PHP_EOL,
            'DB_PREFIX='.$dbPrefix.PHP_EOL,
            'DB_CHARSET='.$dbCharset.PHP_EOL,
            'DB_COLLATE='.$dbCollation.PHP_EOL,
            'DISABLE_CRON='.$disableUpdater.PHP_EOL,
            'DISABLE_FILE_EDIT='.$disableCronjob.PHP_EOL,
            'DISABLE_UPDATER='.$disableFileEditing.PHP_EOL
        ];

        $this->file->put($envFile, $data);
    }
}
