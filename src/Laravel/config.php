<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Query Analyzer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the PHP Query Optimizer
    |
    */

    'enabled' => env('QUERY_ANALYZER_ENABLED', true),

    'rules' => [
        'select_star' => true,
        'missing_where' => true,
        'order_by_without_limit' => true,
        'like_wildcard' => true,
        'join_without_index' => true,
        'subquery' => true,
        'too_many_joins' => true,
    ],

    'thresholds' => [
        'too_many_joins' => 4,
        'score_warning' => 70,
        'score_danger' => 50,
    ],
];
