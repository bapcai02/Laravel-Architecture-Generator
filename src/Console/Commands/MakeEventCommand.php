<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeEventCommand extends Command
{
    protected $signature = 'make:event {name : The name of the event} {--force : Overwrite existing files}';
    protected $description = 'Create a new event and listener';
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

        $this->info("Creating event: {$name}");

        try {
            $createdFiles = $this->generator->generateEvent($name, ['force' => $force]);

            $this->info('Event and listener created successfully!');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create event: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 