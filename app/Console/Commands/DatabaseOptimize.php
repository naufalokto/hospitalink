<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DatabaseOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize 
                            {--run-tests : Run performance tests after optimization}
                            {--clear-cache : Clear all caches before optimization}
                            {--analyze-tables : Analyze table performance}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run comprehensive database optimization';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting comprehensive database optimization...");
        $this->info(str_repeat("=", 60));

        // Step 1: Clear caches if requested
        if ($this->option('clear-cache')) {
            $this->clearAllCaches();
        }

        // Step 2: Run migrations
        $this->runMigrations();

        // Step 3: Optimize database
        $this->optimizeDatabase();

        // Step 4: Analyze tables if requested
        if ($this->option('analyze-tables')) {
            $this->analyzeTables();
        }

        // Step 5: Run performance tests if requested
        if ($this->option('run-tests')) {
            $this->runPerformanceTests();
        }

        $this->info("\n" . str_repeat("=", 60));
        $this->info("Database optimization completed successfully! ✓");
        $this->info(str_repeat("=", 60));
    }

    private function clearAllCaches()
    {
        $this->info("\n1. Clearing all caches...");
        
        try {
            Artisan::call('cache:clear');
            $this->info("   ✓ Application cache cleared");
            
            Artisan::call('config:clear');
            $this->info("   ✓ Configuration cache cleared");
            
            Artisan::call('route:clear');
            $this->info("   ✓ Route cache cleared");
            
            Artisan::call('view:clear');
            $this->info("   ✓ View cache cleared");
            
            // Clear database caches
            Cache::flush();
            $this->info("   ✓ Database cache cleared");
            
        } catch (\Exception $e) {
            $this->error("   ✗ Error clearing caches: " . $e->getMessage());
        }
    }

    private function runMigrations()
    {
        $this->info("\n2. Running database migrations...");
        
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info("   ✓ Migrations completed successfully");
        } catch (\Exception $e) {
            $this->error("   ✗ Migration error: " . $e->getMessage());
        }
    }

    private function optimizeDatabase()
    {
        $this->info("\n3. Optimizing database...");
        
        try {
            // Analyze all tables
            $this->info("   Analyzing tables...");
            $tables = $this->getTables();
            
            foreach ($tables as $table) {
                DB::statement("ANALYZE TABLE `{$table}`");
                $this->line("     ✓ Analyzed table: {$table}");
            }
            
            // Optimize tables
            $this->info("   Optimizing tables...");
            foreach ($tables as $table) {
                DB::statement("OPTIMIZE TABLE `{$table}`");
                $this->line("     ✓ Optimized table: {$table}");
            }
            
            // Update table statistics
            $this->info("   Updating table statistics...");
            DB::statement("FLUSH TABLES");
            $this->info("   ✓ Table statistics updated");
            
        } catch (\Exception $e) {
            $this->error("   ✗ Database optimization error: " . $e->getMessage());
        }
    }

    private function analyzeTables()
    {
        $this->info("\n4. Analyzing table performance...");
        
        try {
            // Run index monitor
            Artisan::call('db:index-monitor');
            $this->info("   ✓ Index analysis completed");
            
            // Get table statistics
            $this->displayTableStatistics();
            
        } catch (\Exception $e) {
            $this->error("   ✗ Table analysis error: " . $e->getMessage());
        }
    }

    private function runPerformanceTests()
    {
        $this->info("\n5. Running performance tests...");
        
        try {
            // Test database performance
            $this->info("   Testing database performance...");
            Artisan::call('db:performance-test', [
                '--queries' => 100,
                '--concurrent' => 5,
                '--test-type' => 'all'
            ]);
            
            // Test cache performance
            $this->info("   Testing cache performance...");
            Artisan::call('cache:performance-test', [
                '--operations' => 1000,
                '--test-type' => 'all'
            ]);
            
            $this->info("   ✓ Performance tests completed");
            
        } catch (\Exception $e) {
            $this->error("   ✗ Performance test error: " . $e->getMessage());
        }
    }

    private function getTables()
    {
        $query = "
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_type = 'BASE TABLE'
        ";
        
        $tables = DB::select($query);
        return array_column($tables, 'table_name');
    }

    private function displayTableStatistics()
    {
        $this->info("\n   Table Statistics:");
        $this->info("   " . str_repeat("-", 50));
        
        $query = "
            SELECT 
                table_name,
                table_rows,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb,
                ROUND((index_length / 1024 / 1024), 2) as index_size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_type = 'BASE TABLE'
            ORDER BY (data_length + index_length) DESC
        ";
        
        $tables = DB::select($query);
        
        $this->table(
            ['Table', 'Rows', 'Total Size (MB)', 'Index Size (MB)'],
            array_map(function($table) {
                return [
                    $table->table_name,
                    number_format($table->table_rows),
                    $table->size_mb,
                    $table->index_size_mb
                ];
            }, $tables)
        );
    }
}
