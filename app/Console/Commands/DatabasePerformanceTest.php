<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Hospital;
use App\Models\Booking;
use App\Models\User;
use App\Models\TransactionDetail;

class DatabasePerformanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:performance-test 
                            {--queries=100 : Number of queries to test}
                            {--concurrent=5 : Number of concurrent connections}
                            {--test-type=all : Type of test (all, hospitals, bookings, users)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database performance with various query patterns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queries = $this->option('queries');
        $concurrent = $this->option('concurrent');
        $testType = $this->option('test-type');

        $this->info("Starting database performance test...");
        $this->info("Queries: {$queries}, Concurrent: {$concurrent}, Type: {$testType}");

        $results = [];

        if ($testType === 'all' || $testType === 'hospitals') {
            $results['hospitals'] = $this->testHospitalQueries($queries, $concurrent);
        }

        if ($testType === 'all' || $testType === 'bookings') {
            $results['bookings'] = $this->testBookingQueries($queries, $concurrent);
        }

        if ($testType === 'all' || $testType === 'users') {
            $results['users'] = $this->testUserQueries($queries, $concurrent);
        }

        $this->displayResults($results);
    }

    private function testHospitalQueries($queries, $concurrent)
    {
        $this->info("Testing hospital queries...");
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $queries; $i++) {
            $queryStart = microtime(true);
            
            // Test different hospital query patterns
            $testType = $i % 4;
            
            switch ($testType) {
                case 0:
                    // Basic hospital query
                    Hospital::with(['roomTypes.roomType'])->get();
                    break;
                case 1:
                    // Hospital by slug
                    Hospital::where('slug', 'like', '%hospital%')->first();
                    break;
                case 2:
                    // Hospital with room data
                    Hospital::with(['roomTypes.roomType'])
                        ->whereHas('roomTypes', function($q) {
                            $q->where('rooms_count', '>', 0);
                        })
                        ->get();
                    break;
                case 3:
                    // Cached hospital query
                    Cache::remember("test_hospital_{$i}", 60, function() {
                        return Hospital::with(['roomTypes.roomType'])->get();
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
            'queries_per_second' => $queries / $totalTime
        ];
    }

    private function testBookingQueries($queries, $concurrent)
    {
        $this->info("Testing booking queries...");
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $queries; $i++) {
            $queryStart = microtime(true);
            
            // Test different booking query patterns
            $testType = $i % 5;
            
            switch ($testType) {
                case 0:
                    // User bookings with relations
                    Booking::with(['hospital', 'roomType', 'payments'])
                        ->where('user_id', 1)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                    break;
                case 1:
                    // Bookings by status
                    Booking::where('status', 'confirmed')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->get();
                    break;
                case 2:
                    // Hospital bookings
                    Booking::where('hospital_id', 1)
                        ->with(['user', 'roomType'])
                        ->get();
                    break;
                case 3:
                    // Transaction details
                    TransactionDetail::with(['booking', 'hospital', 'roomType'])
                        ->where('status', 'completed')
                        ->orderBy('payment_completed_at', 'desc')
                        ->get();
                    break;
                case 4:
                    // Cached booking query
                    Cache::remember("test_booking_{$i}", 60, function() {
                        return Booking::with(['hospital', 'roomType'])->get();
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
            'queries_per_second' => $queries / $totalTime
        ];
    }

    private function testUserQueries($queries, $concurrent)
    {
        $this->info("Testing user queries...");
        
        $times = [];
        $startTime = microtime(true);

        for ($i = 0; $i < $queries; $i++) {
            $queryStart = microtime(true);
            
            // Test different user query patterns
            $testType = $i % 3;
            
            switch ($testType) {
                case 0:
                    // User by email
                    User::where('email', 'like', '%@example.com')->first();
                    break;
                case 1:
                    // Users by role
                    User::where('role', 'patient')->get();
                    break;
                case 2:
                    // Cached user query
                    Cache::remember("test_user_{$i}", 60, function() {
                        return User::with(['bookings'])->get();
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
            'queries_per_second' => $queries / $totalTime
        ];
    }

    private function displayResults($results)
    {
        $this->info("\n" . str_repeat("=", 60));
        $this->info("DATABASE PERFORMANCE TEST RESULTS");
        $this->info(str_repeat("=", 60));

        foreach ($results as $testType => $result) {
            $this->info("\n" . strtoupper($testType) . " QUERIES:");
            $this->info("Total Time: " . number_format($result['total_time'], 3) . " seconds");
            $this->info("Average Time: " . number_format($result['avg_time'], 2) . " ms");
            $this->info("Min Time: " . number_format($result['min_time'], 2) . " ms");
            $this->info("Max Time: " . number_format($result['max_time'], 2) . " ms");
            $this->info("Queries/Second: " . number_format($result['queries_per_second'], 2));
            
            // Performance rating
            if ($result['avg_time'] < 50) {
                $this->info("Performance: EXCELLENT ✓");
            } elseif ($result['avg_time'] < 100) {
                $this->info("Performance: GOOD ✓");
            } elseif ($result['avg_time'] < 200) {
                $this->info("Performance: FAIR ⚠");
            } else {
                $this->info("Performance: POOR ✗");
            }
        }

        $this->info("\n" . str_repeat("=", 60));
        $this->info("RECOMMENDATIONS:");
        
        foreach ($results as $testType => $result) {
            if ($result['avg_time'] > 100) {
                $this->warn("- {$testType}: Consider adding more indexes or optimizing queries");
            }
            if ($result['queries_per_second'] < 10) {
                $this->warn("- {$testType}: Consider implementing caching");
            }
        }
        
        $this->info(str_repeat("=", 60));
    }
}
