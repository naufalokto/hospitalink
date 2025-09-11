<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DatabaseMonitoringServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (config('database_monitoring.enabled')) {
            $this->setupQueryLogging();
            $this->setupCacheMonitoring();
        }
    }

    /**
     * Setup query logging for slow queries
     */
    private function setupQueryLogging()
    {
        if (!config('database_monitoring.logging.log_slow_queries')) {
            return;
        }

        DB::listen(function ($query) {
            $threshold = config('database_monitoring.slow_query_threshold', 1000);
            
            if ($query->time > $threshold) {
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                    'connection' => $query->connectionName,
                ]);
            }
        });
    }

    /**
     * Setup cache monitoring
     */
    private function setupCacheMonitoring()
    {
        // Monitor cache hit/miss ratios
        $this->app->singleton('cache.monitor', function ($app) {
            return new class {
                private $hits = 0;
                private $misses = 0;
                private $operations = 0;

                public function recordHit()
                {
                    $this->hits++;
                    $this->operations++;
                }

                public function recordMiss()
                {
                    $this->misses++;
                    $this->operations++;
                }

                public function getStats()
                {
                    $total = $this->hits + $this->misses;
                    return [
                        'hits' => $this->hits,
                        'misses' => $this->misses,
                        'total' => $total,
                        'hit_ratio' => $total > 0 ? $this->hits / $total : 0,
                    ];
                }

                public function reset()
                {
                    $this->hits = 0;
                    $this->misses = 0;
                    $this->operations = 0;
                }
            };
        });
    }

    /**
     * Get database performance metrics
     */
    public static function getPerformanceMetrics()
    {
        try {
            $metrics = [
                'database' => self::getDatabaseMetrics(),
                'cache' => self::getCacheMetrics(),
                'indexes' => self::getIndexMetrics(),
            ];

            return $metrics;
        } catch (\Exception $e) {
            Log::error('Failed to get performance metrics: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get database metrics
     */
    private static function getDatabaseMetrics()
    {
        $query = "
            SELECT 
                COUNT(*) as total_tables,
                SUM(table_rows) as total_rows,
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as total_size_mb,
                ROUND(SUM(index_length) / 1024 / 1024, 2) as index_size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_type = 'BASE TABLE'
        ";

        $result = DB::select($query);
        return $result[0] ?? null;
    }

    /**
     * Get cache metrics
     */
    private static function getCacheMetrics()
    {
        $monitor = app('cache.monitor');
        return $monitor->getStats();
    }

    /**
     * Get index metrics
     */
    private static function getIndexMetrics()
    {
        $query = "
            SELECT 
                COUNT(*) as total_indexes,
                COUNT(DISTINCT table_name) as tables_with_indexes
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE()
            AND index_name != 'PRIMARY'
        ";

        $result = DB::select($query);
        return $result[0] ?? null;
    }

    /**
     * Clear cache for specific model
     */
    public static function clearModelCache($model, $id = null)
    {
        $clearKeys = config('database_monitoring.cache.clear_on_update');
        $modelName = strtolower(class_basename($model));
        
        if (isset($clearKeys[$modelName])) {
            foreach ($clearKeys[$modelName] as $keyPattern) {
                if ($id && strpos($keyPattern, '{id}') !== false) {
                    $key = str_replace('{id}', $id, $keyPattern);
                    Cache::forget($key);
                } else {
                    Cache::forget($keyPattern);
                }
            }
        }
    }

    /**
     * Get optimization recommendations
     */
    public static function getOptimizationRecommendations()
    {
        $recommendations = [];

        try {
            // Check for missing indexes
            $missingIndexes = self::checkMissingIndexes();
            if (!empty($missingIndexes)) {
                $recommendations[] = [
                    'type' => 'missing_indexes',
                    'message' => 'Missing indexes detected',
                    'details' => $missingIndexes,
                    'priority' => 'high'
                ];
            }

            // Check for unused indexes
            $unusedIndexes = self::checkUnusedIndexes();
            if (!empty($unusedIndexes)) {
                $recommendations[] = [
                    'type' => 'unused_indexes',
                    'message' => 'Unused indexes detected',
                    'details' => $unusedIndexes,
                    'priority' => 'medium'
                ];
            }

            // Check cache performance
            $cacheStats = self::getCacheMetrics();
            if ($cacheStats['hit_ratio'] < 0.8) {
                $recommendations[] = [
                    'type' => 'cache_performance',
                    'message' => 'Low cache hit ratio',
                    'details' => ['hit_ratio' => $cacheStats['hit_ratio']],
                    'priority' => 'medium'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Failed to get optimization recommendations: ' . $e->getMessage());
        }

        return $recommendations;
    }

    /**
     * Check for missing indexes
     */
    private static function checkMissingIndexes()
    {
        $missing = [];
        $indexes = config('database_monitoring.indexes.monitor', []);

        foreach ($indexes as $table => $columns) {
            foreach ($columns as $column) {
                $query = "
                    SELECT COUNT(*) as count
                    FROM information_schema.statistics 
                    WHERE table_schema = DATABASE()
                    AND table_name = ?
                    AND column_name = ?
                ";

                $result = DB::select($query, [$table, $column]);
                if ($result[0]->count == 0) {
                    $missing[] = "{$table}.{$column}";
                }
            }
        }

        return $missing;
    }

    /**
     * Check for unused indexes
     */
    private static function checkUnusedIndexes()
    {
        // This is a simplified check - in production you'd want to use
        // performance_schema or slow query log analysis
        $query = "
            SELECT 
                table_name,
                index_name,
                'Consider removing if not used in queries' as recommendation
            FROM information_schema.statistics s
            WHERE s.table_schema = DATABASE()
            AND s.index_name != 'PRIMARY'
            AND s.index_name NOT LIKE '%_id'
            AND s.index_name NOT LIKE '%_fk'
            ORDER BY table_name, index_name
        ";

        return DB::select($query);
    }
}
