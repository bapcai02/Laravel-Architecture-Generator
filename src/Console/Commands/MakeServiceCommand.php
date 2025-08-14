<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name : The name of the service} {--force : Overwrite existing files}';
    protected $description = 'Create a new service class';
    protected ArchitectureGenerator $generator;

    public function __construct(ArchitectureGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $force = $this->option('force');

        $this->info("Creating service: {$name}");

        try {
            $createdFiles = $this->generator->generateService($name, ['force' => $force]);

            $this->info('Service created successfully!');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create service: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 