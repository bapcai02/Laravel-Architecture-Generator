<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeQueryCommand extends Command
{
    protected $signature = 'make:query {name : The name of the query} {--force : Overwrite existing files}';
    protected $description = 'Create a new CQRS query and handler';
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

        $this->info("Creating CQRS query: {$name}");

        try {
            $createdFiles = $this->generator->generateCQRS($name, ['force' => $force]);

            $this->info('CQRS query and handlers created successfully!');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create query: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 