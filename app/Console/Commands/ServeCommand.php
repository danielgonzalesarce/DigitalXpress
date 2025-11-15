<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve 
                            {--host=127.0.0.1 : The host address to serve the application on}
                            {--port=8081 : The port to serve the application on}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server (default port 8081)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $host = $this->option('host');
        $port = $this->option('port');

        $publicPath = public_path();
        
        // Usar server.php si existe, sino usar public/index.php
        $routerPath = base_path('server.php');
        if (!file_exists($routerPath)) {
            $routerPath = $publicPath . '/index.php';
        }

        $command = [
            PHP_BINARY,
            '-S',
            "{$host}:{$port}",
            '-t',
            $publicPath,
            $routerPath,
        ];

        $this->info("Servidor iniciado en http://{$host}:{$port}");
        $this->info("Presiona Ctrl+C para detener el servidor");

        $process = new Process($command, base_path(), null, null, null);

        try {
            $process->setTty(true);
        } catch (\Throwable $e) {
            // Ignorar si no se puede establecer TTY
        }

        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        return $process->getExitCode();
    }
}
