<?php

require __DIR__ . '/../../../autoload.php';

// php vendor-bin-query-analyze path\to\file.php
use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;
use ProfessionalChacha\PhpQueryOptimizer\CodeAnalyzer;

$query = $argv[1] ?? '';

if (!$query) {
    echo "Usage: php query-analyzer.php \"SQL QUERY\"\n";
    exit;
}

$queryAnalyzer = new QueryAnalyzer();
$queryAnalyzer->loadDefaultRules();

$result = $queryAnalyzer->analyze($query);

echo "Query: " . $query . PHP_EOL;
echo "Issues found: " . count($result['issues']) . PHP_EOL;

foreach ($result['issues'] as $issue) {
    if (is_array($issue)) {
        echo "- " . $issue['issue'] . PHP_EOL;
        echo "  Solution: " . $issue['solution'] . PHP_EOL;
    } else {
        echo "- " . $issue . PHP_EOL;
    }
}