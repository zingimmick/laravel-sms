<?php

declare(strict_types=1);

namespace Zing\LaravelSms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SmsSwitchConnectionCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sms:connection';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            new InputArgument('connection', InputArgument::REQUIRED, 'Which connection to use'),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            new InputOption('show', 's', InputOption::VALUE_NONE, 'Display the sms default connection instead of modifying files'),
            new InputOption('always-no', null, InputOption::VALUE_NONE, 'Skip generating sms default connection if it already exists'),
            new InputOption('force', 'f', InputOption::VALUE_NONE, 'Skip confirmation when overwriting an existing sms default connection'),
        ];
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the sms default connection used to send message';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $connection = $this->argument('connection');

        if ($this->option('show')) {
            $this->comment('SMS_CONNECTION=' . $connection);

            return;
        }

        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayConnection($connection);
        }

        if (Str::contains(file_get_contents($path), 'SMS_CONNECTION') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL . "SMS_CONNECTION={$connection}" . PHP_EOL, FILE_APPEND);
        } else {
            if ($this->option('always-no')) {
                $this->comment('Sms default connection already exists. Skipping...');

                return;
            }

            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to your sms default connection.');

                return;
            }

            // update existing entry
            file_put_contents(
                $path,
                str_replace(
                    'SMS_CONNECTION=' . $this->laravel['config']['sms.default'],
                    'SMS_CONNECTION=' . $connection,
                    file_get_contents($path)
                )
            );
        }//end if

        $this->displayConnection($connection);
    }

    /**
     * Display the key.
     *
     * @param string $key
     *
     * @return void
     */
    protected function displayConnection($key): void
    {
        $this->laravel['config']['sms.default'] = $key;

        $this->info("sms default connection switch to [{$key}] successfully.");
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed()
    {
        return $this->option('force') ? true : $this->confirm(
            'This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }
}
