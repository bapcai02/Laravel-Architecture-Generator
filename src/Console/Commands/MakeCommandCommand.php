<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeCommandCommand extends Command
{
    protected $signature = 'make:command {name : The name of the command} {--force : Overwrite existing files}';
    protected $description = 'Create a new CQRS command and handler';
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

        $this->info("Creating CQRS command: {$name}");

        try {
            $createdFiles = $this->generator->generateCQRS($name, ['force' => $force]);

            $this->info('CQRS command and handlers created successfully!');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create command: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 