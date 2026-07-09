<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'botcms:install';

    /**
     * The console command description.
     */
    protected $description = 'Run a 1-click interactive installation and database setup for BotCMS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Welcome to the BotCMS Platform Installer!');

        // 1. Check if .env exists, if not copy from .env.example
        if (!File::exists(base_path('.env'))) {
            $this->components->task('Creating .env file from template', function () {
                File::copy(base_path('.env.example'), base_path('.env'));
                return true;
            });
        }

        // 2. Select Database Driver
        $dbDriver = $this->choice(
            'Which database system would you like to use?',
            ['sqlite' => 'SQLite (Default, Zero-Config)', 'mysql' => 'MySQL', 'pgsql' => 'PostgreSQL'],
            'sqlite'
        );

        $dbConfig = [];

        if ($dbDriver === 'sqlite') {
            $dbConfig = [
                'DB_CONNECTION' => 'sqlite',
                'DB_DATABASE' => 'database/database.sqlite',
            ];
            
            // Create sqlite database file if it doesn't exist
            $dbPath = database_path('database.sqlite');
            if (!File::exists($dbPath)) {
                $this->components->task('Creating SQLite database file', function () use ($dbPath) {
                    File::ensureDirectoryExists(dirname($dbPath));
                    File::put($dbPath, '');
                    return true;
                });
            }
        } else {
            $defaultPort = $dbDriver === 'pgsql' ? '5432' : '3306';
            $defaultUser = $dbDriver === 'pgsql' ? 'postgres' : 'root';

            $host = $this->ask('Enter Database Host', '127.0.0.1');
            $port = $this->ask('Enter Database Port', $defaultPort);
            $database = $this->ask('Enter Database Name', 'botcms');
            $username = $this->ask('Enter Database Username', $defaultUser);
            $password = $this->secret('Enter Database Password') ?? '';

            $dbConfig = [
                'DB_CONNECTION' => $dbDriver,
                'DB_HOST' => $host,
                'DB_PORT' => $port,
                'DB_DATABASE' => $database,
                'DB_USERNAME' => $username,
                'DB_PASSWORD' => $password,
            ];

            // Update configurations at runtime so we can test the connection
            config([
                "database.connections.{$dbDriver}.host" => $host,
                "database.connections.{$dbDriver}.port" => $port,
                "database.connections.{$dbDriver}.database" => $database,
                "database.connections.{$dbDriver}.username" => $username,
                "database.connections.{$dbDriver}.password" => $password,
            ]);

            // Test Database Connection
            $this->components->task('Testing database connection', function () use ($dbDriver) {
                try {
                    DB::connection($dbDriver)->getPdo();
                    return true;
                } catch (\Exception $e) {
                    $this->error("\nFailed to connect: " . $e->getMessage());
                    return false;
                }
            });
        }

        // 3. Write configuration details to .env
        $this->components->task('Writing configuration details to .env', function () use ($dbConfig) {
            $envPath = base_path('.env');
            $envContent = File::get($envPath);

            foreach ($dbConfig as $key => $value) {
                // If key exists, replace it, else append it
                if (preg_match("/^{$key}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
                } else {
                    $envContent .= "\n{$key}=\"{$value}\"";
                }
            }

            File::put($envPath, $envContent);
            return true;
        });

        // 4. Generate Application Key
        $this->call('key:generate', ['--force' => true]);

        // 5. Run Migrations & Seeders
        $this->components->task('Running database migrations and seeders', function () {
            try {
                $this->callSilent('migrate:fresh', ['--force' => true]);
                $this->callSilent('db:seed', ['--force' => true]);
                return true;
            } catch (\Exception $e) {
                $this->error("\nMigration error: " . $e->getMessage());
                return false;
            }
        });

        $this->newLine();
        $this->components->info('BotCMS Platform successfully installed!');
        
        $this->table(
            ['Parameter', 'Default Value'],
            [
                ['Access URL', 'http://127.0.0.1:8000'],
                ['Admin URL', 'http://127.0.0.1:8000/login'],
                ['Admin Email', 'admin@botcms.local'],
                ['Admin Password', 'admin123'],
            ]
        );

        $this->newLine();
        if ($this->confirm('Would you like to start the local development server now?', true)) {
            $this->info('Starting server on http://127.0.0.1:8000...');
            $this->call('serve', ['--port' => 8000]);
        }
    }
}
