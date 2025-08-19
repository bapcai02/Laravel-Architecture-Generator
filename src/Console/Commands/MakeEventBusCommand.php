<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeEventBusCommand extends Command
{
    protected $signature = 'architex:event-bus {name : The base name for the event and listener} {--force : Overwrite existing files}';
    protected $description = 'Create an event and its corresponding listener (Event Bus)';
    protected ArchitectureGenerator $generator;

    public function __construct(ArchitectureGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $force = (bool) $this->option('force');

        $this->info("Generating Event Bus for: {$name}");

        try {
            $createdFiles = $this->generator->generateEvent($name, ['force' => $force]);

            $this->info('Event and listener created successfully:');
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Failed to generate Event Bus: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}


