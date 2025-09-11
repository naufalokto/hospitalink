<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\Hospital;
use App\Models\Booking;
use App\Models\User;

class CachePerformanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:performance-test 
                            {--operations=1000 : Number of cache operations to test}
                            {--test-type=all : Type of test (all, read, write, mixed)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cache performance with various operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $operations = $this->option('operations');
        $testType = $this->option('test-type');

        $this->info("Starting cache performance test...");
        $this->info("Operations: {$operations}, Type: {$testType}");

        $results = [];

        if ($testType === 'all' || $testType === 'write') {
            $results['write'] = $this->testWriteOperations($operations);
        }

        if ($testType === 'all' || $testType === 'read') {
            $results['read'] = $this->testReadOperations($operations);
        }

        if ($testType === 'all' || $testType === 'mixed') {
            $results['mixed'] = $this->testMixedOperations($operations);
        }

        $this->displayResults($results);
    }

    private function testWriteOperations($operations)
    {
        $this->info("Testing cache write operations...");
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $operations; $i++) {
            $queryStart = microtime(true);
            
            // Test different write operations
            $testType = $i % 4;
            
            switch ($testType) {
                case 0:
                    // Simple key-value
                    Cache::put("test_key_{$i}", "test_value_{$i}", 60);
                    break;
                case 1:
                    // Array data
                    Cache::put("test_array_{$i}", [
                        'id' => $i,
                        'name' => "Test {$i}",
                        'data' => str_repeat('x', 100)
                    ], 60);
                    break;
                case 2:
                    // Hospital data (real data)
                    $hospital = Hospital::with(['roomTypes.roomType'])->first();
                    if ($hospital) {
                        Cache::put("hospital_data_{$i}", $hospital, 60);
                    }
                    break;
                case 3:
                    // Remember operation
                    Cache::remember("remember_key_{$i}", 60, function() use ($i) {
                        return "remembered_value_{$i}";
                    });
                    break;
            }
            
            $queryTime = (microtime(true) - $queryStart) * 1000;
            $times[] = $queryTime;
        }

        $totalTime = microtime(true) - $startTime;
        
        return [
            'total_time' => $totalTime,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times),
            'operations_per_second' => $operations / $totalTime
        ];
    }

    private function testReadOperations($operations)
    {
        $this->info("Testing cache read operations...");
        
        // Pre-populate cache with test data
        for ($i = 0; $i < 100; $i++) {
            Cache::put("read_test_key_{$i}", "read_test_value_{$i}", 60);
        }
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $operations; $i++) {
            $queryStart = microtime(true);
            
            // Test different read operations
            $testType = $i % 3;
            
            switch ($testType) {
                case 0:
                    // Simple get
                    Cache::get("read_test_key_" . ($i % 100));
                    break;
                case 1:
                    // Get with default
                    Cache::get("read_test_key_" . ($i % 100), "default_value");
                    break;
                case 2:
                    // Has check
                    Cache::has("read_test_key_" . ($i % 100));
                    break;
            }
            
            $queryTime = (microtime(true) - $queryStart) * 1000;
            $times[] = $queryTime;
        }

        $totalTime = microtime(true) - $startTime;
        
        return [
            'total_time' => $totalTime,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times),
            'operations_per_second' => $operations / $totalTime
        ];
    }

    private function testMixedOperations($operations)
    {
        $this->info("Testing mixed cache operations...");
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $operations; $i++) {
            $queryStart = microtime(true);
            
            // Test mixed operations (70% read, 30% write)
            $operationType = $i % 10;
            
            if ($operationType < 7) {
                // Read operations
                $key = "mixed_test_key_" . ($i % 50);
                Cache::get($key, "default_value");
            } else {
                // Write operations
                $key = "mixed_test_key_" . $i;
                Cache::put($key, "mixed_test_value_{$i}", 60);
            }
            
            $queryTime = (microtime(true) - $queryStart) * 1000;
            $times[] = $queryTime;
        }

        $totalTime = microtime(true) - $startTime;
        
        return [
            'total_time' => $totalTime,
            'avg_time' => array_sum($times) / count($times),
            'min_time' => min($times),
            'max_time' => max($times),
            'operations_per_second' => $operations / $totalTime
        ];
    }

    private function displayResults($results)
    {
        $this->info("\n" . str_repeat("=", 60));
        $this->info("CACHE PERFORMANCE TEST RESULTS");
        $this->info(str_repeat("=", 60));

        foreach ($results as $testType => $result) {
            $this->info("\n" . strtoupper($testType) . " OPERATIONS:");
            $this->info("Total Time: " . number_format($result['total_time'], 3) . " seconds");
            $this->info("Average Time: " . number_format($result['avg_time'], 2) . " ms");
            $this->info("Min Time: " . number_format($result['min_time'], 2) . " ms");
            $this->info("Max Time: " . number_format($result['max_time'], 2) . " ms");
            $this->info("Operations/Second: " . number_format($result['operations_per_second'], 2));
            
            // Performance rating
            if ($result['avg_time'] < 1) {
                $this->info("Performance: EXCELLENT ✓");
            } elseif ($result['avg_time'] < 5) {
                $this->info("Performance: GOOD ✓");
            } elseif ($result['avg_time'] < 10) {
                $this->info("Performance: FAIR ⚠");
            } else {
                $this->info("Performance: POOR ✗");
            }
        }

        $this->info("\n" . str_repeat("=", 60));
        $this->info("CACHE STATISTICS:");
        
        // Get cache statistics
        $this->displayCacheStats();
        
        $this->info("\n" . str_repeat("=", 60));
        $this->info("RECOMMENDATIONS:");
        
        foreach ($results as $testType => $result) {
            if ($result['avg_time'] > 5) {
                $this->warn("- {$testType}: Consider optimizing cache driver or configuration");
            }
            if ($result['operations_per_second'] < 100) {
                $this->warn("- {$testType}: Consider using Redis for better performance");
            }
        }
        
        $this->info(str_repeat("=", 60));
    }

    private function displayCacheStats()
    {
        try {
            // Test cache driver
            $driver = config('cache.default');
            $this->info("Cache Driver: {$driver}");
            
            // Test cache size (approximate)
            $testKey = 'cache_size_test_' . time();
            Cache::put($testKey, str_repeat('x', 1024), 60); // 1KB test
            
            $startTime = microtime(true);
            $retrieved = Cache::get($testKey);
            $readTime = (microtime(true) - $startTime) * 1000;
            
            $this->info("Cache Read Time: " . number_format($readTime, 2) . " ms");
            $this->info("Cache Working: " . ($retrieved ? "YES ✓" : "NO ✗"));
            
            // Clean up test key
            Cache::forget($testKey);
            
        } catch (\Exception $e) {
            $this->error("Cache Error: " . $e->getMessage());
        }
    }
}
