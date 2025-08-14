<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeDDDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ddd {module : The name of the DDD module} 
                            {--force : Overwrite existing files}
                            {--layers= : Specific layers to generate (domain,application,infrastructure,ui)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DDD module structure with all layers';

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
        $module = $this->argument('module');
        $force = $this->option('force');
        $layers = $this->option('layers');

        $this->info("Creating DDD module: {$module}");

        try {
            $options = [
                'force' => $force,
            ];

            if ($layers) {
                $options['layers'] = explode(',', $layers);
            }

            $createdFiles = $this->generator->generateDDD($module, $options);

            $this->info('DDD module created successfully!');
            $this->line('Created files:');
            
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }

            $this->info("\nDDD Module Structure:");
            $this->line("├── Domain/");
            $this->line("│   ├── Entities/");
            $this->line("│   ├── Repositories/");
            $this->line("│   ├── Services/");
            $this->line("│   ├── Events/");
            $this->line("│   └── Exceptions/");
            $this->line("├── Application/");
            $this->line("│   ├── Services/");
            $this->line("│   ├── Commands/");
            $this->line("│   ├── Queries/");
            $this->line("│   └── Handlers/");
            $this->line("├── Infrastructure/");
            $this->line("│   ├── Repositories/");
            $this->line("│   ├── Services/");
            $this->line("│   ├── Persistence/");
            $this->line("│   └── External/");
            $this->line("└── UI/");
            $this->line("    ├── Controllers/");
            $this->line("    ├── Requests/");
            $this->line("    ├── Resources/");
            $this->line("    └── Middleware/");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create DDD module: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
} 