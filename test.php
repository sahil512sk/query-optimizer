<?php

require __DIR__ . '/vendor/autoload.php';

use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;

$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();

$queries = [
    "SELECT * FROM users",
    "SELECT name FROM users WHERE id = 1",
    "SELECT * FROM users ORDER BY created_at",
    "SELECT * FROM users WHERE email LIKE '%gmail.com'"
];

foreach ($queries as $query) {
    echo "Query: $query\n";
    $result = $analyzer->analyze($query);
    print_r($result);
    echo "----------------------\n";
}