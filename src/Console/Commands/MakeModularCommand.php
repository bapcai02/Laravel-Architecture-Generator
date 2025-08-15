<?php

namespace LaravelArchitex\Console\Commands;

use Illuminate\Console\Command;
use LaravelArchitex\Services\ArchitectureGenerator;

class MakeModularCommand extends Command
{
    protected $signature = 'architex:modular {name} {--path=} {--namespace=} {--with-tests} {--with-migrations} {--with-seeders} {--with-routes} {--with-config} {--with-views} {--with-assets}';
    
    protected $description = 'Generate a complete modular/package-based architecture structure';

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

        $this->info("Generating modular architecture for: {$name}");
        
        try {
            $createdFiles = $this->generator->generateModular($name, $options);
            
            $this->info("✅ Modular architecture generated successfully!");
            $this->info("📁 Created files:");
            
            foreach ($createdFiles as $file) {
                $this->line("  - {$file}");
            }
            
            $this->info("\n🎯 Next steps:");
            $this->line("  1. Register the module in your AppServiceProvider");
            $this->line("  2. Add module routes to your main routes file");
            $this->line("  3. Publish module assets if needed");
            $this->line("  4. Run migrations: php artisan migrate");
            
        } catch (\Exception $e) {
            $this->error("❌ Error generating modular architecture: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 