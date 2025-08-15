<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeHexagonalCommand extends Command
{
    protected $signature = 'architex:hexagonal {name} {--path=} {--namespace=} {--with-tests} {--with-migrations} {--with-seeders} {--with-routes} {--with-config} {--with-views} {--with-assets}';
    protected $description = 'Generate a complete Hexagonal Architecture (Ports and Adapters) structure';
    protected ArchitectureGenerator $generator;

    public function __construct(ArchitectureGenerator $generator)
    {
        parent::__construct();
        $this->generator = $generator;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $options = [
            'path' => $this->option('path'),
            'namespace' => $this->option('namespace'),
            'with_tests' => $this->option('with-tests'),
            'with_migrations' => $this->option('with-migrations'),
            'with_seeders' => $this->option('with-seeders'),
            'with_routes' => $this->option('with-routes'),
            'with_config' => $this->option('with-config'),
            'with_views' => $this->option('with-views'),
            'with_assets' => $this->option('with-assets'),
        ];
        
        $this->info("Generating Hexagonal Architecture for: {$name}");
        
        try {
            $createdFiles = $this->generator->generateHexagonal($name, $options);
            $this->info("✅ Hexagonal Architecture generated successfully!");
            $this->info("📁 Created files:");
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }
            $this->info("\n🎯 Next steps:");
            $this->line("  1. Register the ports and adapters in your service provider");
            $this->line("  2. Implement the domain logic in the Application layer");
            $this->line("  3. Configure the adapters in the Infrastructure layer");
            $this->line("  4. Set up the primary adapters in the UI layer");
        } catch (\Exception $e) {
            $this->error("❌ Error generating Hexagonal Architecture: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
} 