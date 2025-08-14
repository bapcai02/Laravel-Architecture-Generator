<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeCQRSCommand extends Command
{
    protected $signature = 'make:cqrs {name : The name of the CQRS module} {--force : Overwrite existing files}';
    protected $description = 'Create a new CQRS structure with commands, queries and handlers';
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

        $this->info("Creating CQRS structure: {$name}");

        try {
            $createdFiles = $this->generator->generateCQRS($name, ['force' => $force]);

            $this->info('CQRS structure created successfully!');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create CQRS structure: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 