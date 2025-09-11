<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for database performance monitoring and optimization
    |
    */

    'enabled' => env('DB_MONITORING_ENABLED', true),

    'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 1000), // milliseconds

    'performance_testing' => [
        'enabled' => env('DB_PERFORMANCE_TESTING_ENABLED', true),
        'default_queries' => env('DB_PERFORMANCE_DEFAULT_QUERIES', 100),
        'default_concurrent' => env('DB_PERFORMANCE_DEFAULT_CONCURRENT', 5),
    ],

    'cache_testing' => [
        'enabled' => env('CACHE_PERFORMANCE_TESTING_ENABLED', true),
        'default_operations' => env('CACHE_PERFORMANCE_DEFAULT_OPERATIONS', 1000),
    ],

    'index_monitoring' => [
        'enabled' => env('DB_INDEX_MONITORING_ENABLED', true),
        'check_unused' => env('DB_INDEX_CHECK_UNUSED', true),
        'check_duplicates' => env('DB_INDEX_CHECK_DUPLICATES', true),
    ],

    'optimization' => [
        'enabled' => env('DB_OPTIMIZATION_ENABLED', true),
        'auto_analyze' => env('DB_AUTO_ANALYZE', true),
        'auto_optimize' => env('DB_AUTO_OPTIMIZE', false), // Be careful with this
        'schedule_optimization' => env('DB_SCHEDULE_OPTIMIZATION', true),
    ],

    'alerts' => [
        'enabled' => env('DB_ALERTS_ENABLED', false),
        'email' => env('DB_ALERTS_EMAIL'),
        'slack_webhook' => env('DB_ALERTS_SLACK_WEBHOOK'),
        'thresholds' => [
            'slow_query_count' => env('DB_ALERT_SLOW_QUERY_COUNT', 10),
            'avg_query_time' => env('DB_ALERT_AVG_QUERY_TIME', 500), // milliseconds
            'cache_hit_ratio' => env('DB_ALERT_CACHE_HIT_RATIO', 0.8), // 80%
        ],
    ],

    'logging' => [
        'enabled' => env('DB_LOGGING_ENABLED', true),
        'log_slow_queries' => env('DB_LOG_SLOW_QUERIES', true),
        'log_performance_tests' => env('DB_LOG_PERFORMANCE_TESTS', true),
        'log_index_analysis' => env('DB_LOG_INDEX_ANALYSIS', true),
    ],

    'tables' => [
        'monitor' => [
            'users',
            'hospitals',
            'bookings',
            'payments',
            'transaction_details',
            'booking_rooms',
            'hospital_room_types',
            'room_types',
            'facilities',
            'news',
        ],
        'exclude' => [
            'migrations',
            'password_resets',
            'failed_jobs',
            'personal_access_tokens',
        ],
    ],

    'indexes' => [
        'monitor' => [
            'users' => ['email', 'role', 'google_id', 'facebook_id', 'twitter_id'],
            'hospitals' => ['slug', 'name'],
            'bookings' => ['user_id', 'hospital_id', 'status', 'created_at'],
            'payments' => ['order_id', 'booking_id', 'status'],
            'transaction_details' => ['user_id', 'hospital_id', 'status', 'payment_completed_at'],
        ],
    ],

    'cache' => [
        'keys' => [
            'hospitals_with_rooms' => 1800, // 30 minutes
            'hospital_rooms_{id}' => 300, // 5 minutes
            'user_bookings_{id}' => 300, // 5 minutes
            'booking_stats_{id}' => 900, // 15 minutes
            'hospital_stats_{id}' => 600, // 10 minutes
            'popular_hospitals' => 3600, // 1 hour
        ],
        'clear_on_update' => [
            'hospitals' => ['hospitals_with_rooms', 'hospital_rooms_{id}', 'popular_hospitals'],
            'bookings' => ['user_bookings_{id}', 'booking_stats_{id}', 'hospital_stats_{id}'],
            'payments' => ['user_bookings_{id}', 'booking_stats_{id}'],
        ],
    ],
];
