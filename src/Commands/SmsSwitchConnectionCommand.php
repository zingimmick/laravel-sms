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
     * @return \Symfony\Component\Console\Input\InputArgument[]
     */
    protected function getArguments(): array
    {
        return [new InputArgument('connection', InputArgument::REQUIRED, 'Which connection to use')];
    }

    /**
     * Get the console command options.
     *
     * @return \Symfony\Component\Console\Input\InputOption[]
     */
    protected function getOptions(): array
    {
        return [
            new InputOption(
                'show',
                's',
                InputOption::VALUE_NONE,
                'Display the sms default connection instead of modifying files'
            ),
            new InputOption(
                'always-no',
                null,
                InputOption::VALUE_NONE,
                'Skip generating sms default connection if it already exists'
            ),
            new InputOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Skip confirmation when overwriting an existing sms default connection'
            ),
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
     */
    public function handle(): void
    {
        $connection = $this->argument('connection');
        if (is_array($connection)) {
            return;
        }

        if ($connection === null) {
            return;
        }

        if ($this->option('show')) {
            $this->comment('SMS_CONNECTION=' . $connection);

            return;
        }

        $path = $this->envPath();
        if (! file_exists($path)) {
            $this->displayConnection($connection);

            return;
        }

        if ($this->putEnvToFile($connection, $path)) {
            $this->displayConnection($connection);
        }
    }

    /**
     * put default sms connection to the .env file path.
     *
     * @param string $connection the default sms connection
     * @param string $path the .env file path.
     */
    protected function putEnvToFile(string $connection, string $path): bool
    {
        /** @var string $contents */
        $contents = file_get_contents($path);

        if (! Str::contains($contents, 'SMS_CONNECTION')) {
            // create new entry
            file_put_contents($path, PHP_EOL . sprintf('SMS_CONNECTION=%s', $connection) . PHP_EOL, FILE_APPEND);

            return true;
        }

        if ($this->option('always-no')) {
            $this->comment('Sms default connection already exists. Skipping...');

            return false;
        }

        if (! $this->isConfirmed()) {
            $this->comment('Phew... No changes were made to your sms default connection.');

            return false;
        }

        file_put_contents(
            $path,
            str_replace(
                'SMS_CONNECTION=' . $this->laravel['config']['sms.default'],
                'SMS_CONNECTION=' . $connection,
                $contents
            )
        );

        return true;
    }

    /**
     * Display the key.
     */
    protected function displayConnection(string $connection): void
    {
        $this->laravel['config']['sms.default'] = $connection;

        $this->info(sprintf('sms default connection switch to [%s] successfully.', $connection));
    }

    /**
     * Check if the modification is confirmed.
     */
    protected function isConfirmed(): bool
    {
        if ($this->option('force')) {
            return true;
        }

        return $this->confirm(
            'This maybe invalidate existing sms feature. Are you sure you want to override the sms default connection?'
        );
    }

    /**
     * Get the .env file path.
     */
    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }
}
