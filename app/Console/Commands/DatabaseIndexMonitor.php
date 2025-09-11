<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseIndexMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:index-monitor 
                            {--table= : Specific table to check}
                            {--show-unused : Show unused indexes}
                            {--show-duplicates : Show duplicate indexes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor database indexes and their usage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->option('table');
        $showUnused = $this->option('show-unused');
        $showDuplicates = $this->option('show-duplicates');

        $this->info("Database Index Monitor");
        $this->info(str_repeat("=", 60));

        if ($table) {
            $this->analyzeTable($table);
        } else {
            $this->analyzeAllTables();
        }

        if ($showUnused) {
            $this->showUnusedIndexes();
        }

        if ($showDuplicates) {
            $this->showDuplicateIndexes();
        }
    }

    private function analyzeAllTables()
    {
        $tables = $this->getTables();
        
        $this->info("\nTABLE ANALYSIS:");
        $this->info(str_repeat("-", 60));

        foreach ($tables as $table) {
            $this->analyzeTable($table['name']);
        }
    }

    private function analyzeTable($tableName)
    {
        $this->info("\nTable: {$tableName}");
        $this->info(str_repeat("-", 40));

        // Get table information
        $tableInfo = $this->getTableInfo($tableName);
        $this->info("Rows: " . number_format($tableInfo['rows']));
        $this->info("Data Size: " . $this->formatBytes($tableInfo['data_length']));
        $this->info("Index Size: " . $this->formatBytes($tableInfo['index_length']));
        $this->info("Total Size: " . $this->formatBytes($tableInfo['data_length'] + $tableInfo['index_length']));

        // Get indexes
        $indexes = $this->getTableIndexes($tableName);
        
        if (empty($indexes)) {
            $this->warn("No indexes found for this table!");
            return;
        }

        $this->info("\nIndexes:");
        $this->table(
            ['Name', 'Type', 'Columns', 'Cardinality', 'Size'],
            array_map(function($index) {
                return [
                    $index['name'],
                    $index['type'],
                    $index['columns'],
                    number_format($index['cardinality']),
                    $this->formatBytes($index['size'])
                ];
            }, $indexes)
        );

        // Analyze index efficiency
        $this->analyzeIndexEfficiency($tableName, $indexes);
    }

    private function getTables()
    {
        $query = "
            SELECT 
                table_name as name,
                table_rows as rows,
                ROUND(((data_length + index_length) / 1024 / 1024), 2) as size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_type = 'BASE TABLE'
            ORDER BY (data_length + index_length) DESC
        ";

        return DB::select($query);
    }

    private function getTableInfo($tableName)
    {
        $query = "
            SELECT 
                table_rows as rows,
                data_length,
                index_length,
                (data_length + index_length) as total_length
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_name = ?
        ";

        $result = DB::select($query, [$tableName]);
        return $result[0] ?? [
            'rows' => 0,
            'data_length' => 0,
            'index_length' => 0,
            'total_length' => 0
        ];
    }

    private function getTableIndexes($tableName)
    {
        $query = "
            SELECT 
                index_name as name,
                index_type as type,
                GROUP_CONCAT(column_name ORDER BY seq_in_index) as columns,
                MAX(cardinality) as cardinality,
                SUM(stat_value * @@innodb_page_size) as size
            FROM information_schema.statistics s
            LEFT JOIN information_schema.innodb_sys_tablestats ist 
                ON s.table_name = ist.name
            WHERE s.table_schema = DATABASE()
            AND s.table_name = ?
            GROUP BY index_name, index_type
            ORDER BY size DESC
        ";

        return DB::select($query, [$tableName]);
    }

    private function analyzeIndexEfficiency($tableName, $indexes)
    {
        $this->info("\nIndex Efficiency Analysis:");

        foreach ($indexes as $index) {
            $efficiency = $this->calculateIndexEfficiency($index);
            
            if ($efficiency['score'] >= 80) {
                $status = "EXCELLENT ✓";
                $color = 'green';
            } elseif ($efficiency['score'] >= 60) {
                $status = "GOOD ✓";
                $color = 'yellow';
            } elseif ($efficiency['score'] >= 40) {
                $status = "FAIR ⚠";
                $color = 'yellow';
            } else {
                $status = "POOR ✗";
                $color = 'red';
            }

            $this->line("  {$index['name']}: {$status}");
            
            if ($efficiency['recommendations']) {
                foreach ($efficiency['recommendations'] as $recommendation) {
                    $this->warn("    - {$recommendation}");
                }
            }
        }
    }

    private function calculateIndexEfficiency($index)
    {
        $score = 100;
        $recommendations = [];

        // Check cardinality
        if ($index['cardinality'] < 10) {
            $score -= 30;
            $recommendations[] = "Low cardinality - consider if this index is needed";
        }

        // Check size
        if ($index['size'] > 10 * 1024 * 1024) { // 10MB
            $score -= 20;
            $recommendations[] = "Large index size - consider optimizing";
        }

        // Check if it's a primary key
        if ($index['type'] === 'BTREE' && strpos($index['name'], 'PRIMARY') !== false) {
            $score += 10; // Primary keys are always good
        }

        // Check for duplicate columns
        $columns = explode(',', $index['columns']);
        if (count($columns) !== count(array_unique($columns))) {
            $score -= 40;
            $recommendations[] = "Duplicate columns in index";
        }

        // Check for too many columns
        if (count($columns) > 5) {
            $score -= 15;
            $recommendations[] = "Too many columns - consider splitting into multiple indexes";
        }

        return [
            'score' => max(0, min(100, $score)),
            'recommendations' => $recommendations
        ];
    }

    private function showUnusedIndexes()
    {
        $this->info("\nUNUSED INDEXES:");
        $this->info(str_repeat("-", 40));

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
            ORDER BY table_name, index_name
        ";

        $unused = DB::select($query);
        
        if (empty($unused)) {
            $this->info("No potentially unused indexes found.");
        } else {
            $this->table(
                ['Table', 'Index', 'Recommendation'],
                array_map(function($index) {
                    return [
                        $index->table_name,
                        $index->index_name,
                        $index->recommendation
                    ];
                }, $unused)
            );
        }
    }

    private function showDuplicateIndexes()
    {
        $this->info("\nDUPLICATE INDEXES:");
        $this->info(str_repeat("-", 40));

        $query = "
            SELECT 
                table_name,
                GROUP_CONCAT(index_name) as indexes,
                GROUP_CONCAT(column_name ORDER BY seq_in_index) as columns
            FROM information_schema.statistics s
            WHERE s.table_schema = DATABASE()
            GROUP BY table_name, GROUP_CONCAT(column_name ORDER BY seq_in_index)
            HAVING COUNT(DISTINCT index_name) > 1
        ";

        $duplicates = DB::select($query);
        
        if (empty($duplicates)) {
            $this->info("No duplicate indexes found.");
        } else {
            $this->table(
                ['Table', 'Indexes', 'Columns'],
                array_map(function($index) {
                    return [
                        $index->table_name,
                        $index->indexes,
                        $index->columns
                    ];
                }, $duplicates)
            );
        }
    }

    private function formatBytes($bytes)
    {
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = floor(log($bytes, 1024));
        
        return number_format($bytes / pow(1024, $unitIndex), 2) . ' ' . $units[$unitIndex];
    }
}
