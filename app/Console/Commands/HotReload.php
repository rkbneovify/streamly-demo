<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class HotReload extends Command
{
    /**
     * Command name
     * Run using: php artisan serve:hot
     */
    protected $signature = 'serve:hot';

    /**
     * Command description
     */
    protected $description = 'Serve Laravel with hot reload using Browsersync and auto-open browser';

    public function handle()
    {
        /**
         * ================================
         * REQUIREMENTS
         * ================================
         * 1. PHP 7+
         * 2. Node.js installed
         * 3. Browsersync installed:
         *    - npm install browser-sync --save-dev
         *      OR
         *    - npm install -g browser-sync
         *
         * IMPORTANT:
         * - Hot reload works ONLY through Browsersync
         * - Always open Browsersync URL (port 3000)
         */

        $host = '127.0.0.1';

        // Laravel runs on this port
        $laravelPort = 8000;

        // Browsersync runs on this port (DO NOT use same port as Laravel)
        $browserSyncPort = 3000;

        $this->info('Starting Laravel development server...');

        /**
         * ================================
         * START LARAVEL SERVER
         * ================================
         * This runs:
         * php artisan serve --host=127.0.0.1 --port=8000
         */
        $laravelProcess = new Process([
            'php',
            'artisan',
            'serve',
            '--host=' . $host,
            '--port=' . $laravelPort,
        ]);

        // Start Laravel server in background
        $laravelProcess->start();

        // Give Laravel time to boot before starting Browsersync
        sleep(2);

        $this->info("Laravel running at http://{$host}:{$laravelPort}");

        /**
         * ================================
         * START BROWSERSYNC
         * ================================
         * Browsersync acts as a proxy:
         *
         * Browser → Browsersync (3000) → Laravel (8000)
         *
         * Hot reload ONLY works on Browsersync URL
         */
        $browserSyncProcess = new Process([
            'npx',
            'browser-sync',
            'start',
            '--proxy',
            "{$host}:{$laravelPort}",
            '--port',
            $browserSyncPort,

            // Files to watch for reload
            '--files',
            'app/**/*.php,' .
            'resources/views/**/*.blade.php,' .
            'routes/**/*.php,' .
            'public/css/**/*.css,' .
            'public/js/**/*.js',

            // Delay reload slightly to avoid race conditions
            '--reload-delay',
            '500',

            // Prevent Browsersync from opening its own browser
            '--no-open',
        ]);

        // Start Browsersync in background
        $browserSyncProcess->start();

        $this->info("Browsersync running at http://{$host}:{$browserSyncPort}");
        $this->info('Watching files for changes (hot reload enabled)...');

        /**
         * ================================
         * AUTO-OPEN BROWSER
         * ================================
         * Opens Browsersync URL automatically
         */
        $url = "http://{$host}:{$browserSyncPort}";

        if (PHP_OS_FAMILY === 'Windows') {
            exec("start {$url}");
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            exec("open {$url}");
        } else {
            exec("xdg-open {$url}");
        }

        /**
         * ================================
         * KEEP COMMAND ALIVE
         * ================================
         * Prevent Artisan command from exiting
         * while Laravel or Browsersync is running
         */
        while ($laravelProcess->isRunning() || $browserSyncProcess->isRunning()) {
            usleep(500000); // 0.5 second sleep
        }

        return 0;
    }
}
