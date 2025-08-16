<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The name of the repository} 
                            {--model= : The model name for the repository}
                            {--service : Create service layer with repository}
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository interface and implementation';

    /**
     * The architecture generator instance.
     *
     * @var ArchitectureGenerator
     */
    protected ArchitectureGenerator $generator;

    /**
     * Create a new command instance.
     */
    public function __construct(ArchitectureGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $model = $this->option('model') ?: $name;
        $force = $this->option('force');
        $withService = $this->option('service');

        $this->info("Creating repository for: {$name}");
        if ($withService) {
            $this->info("With service layer");
        }

        try {
            $createdFiles = $this->generator->generateRepository($name, [
                'model' => $model,
                'force' => $force,
                'with_service' => $withService,
            ]);

            $this->info('Repository created successfully!');
            $this->line('Created files:');
            
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            $this->info("\nNext steps:");
            $this->line("1. Register the repository in your service provider");
            $this->line("2. Implement the repository methods");
            if ($withService) {
                $this->line("3. Register the service in your service provider");
                $this->line("4. Use dependency injection in your controllers");
            } else {
                $this->line("3. Use dependency injection in your services");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create repository: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 