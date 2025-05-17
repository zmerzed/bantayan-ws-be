<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class RefreshDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:refresh-database';

    /**
     * The console command description.
     */
    protected $description = 'Drop and create the database again';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get DB connection settings
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Temporarily set database name to 'mysql' to execute drop/create
        Config::set('database.connections.mysql.database', null);
        DB::purge('mysql');

        try {
            $pdo = DB::connection('mysql')->getPdo();

            // Drop database
            $pdo->exec("DROP DATABASE IF EXISTS `$database`");
            $this->info("Database '$database' dropped.");

            // Create database
            $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Database '$database' created.");

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        // Restore config
        Config::set('database.connections.mysql.database', $database);
        DB::purge('mysql');

        $this->info('Database refresh complete.');
        return 0;
    }
}
