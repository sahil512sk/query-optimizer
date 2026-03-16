<?php

require __DIR__ . '/vendor/autoload.php';

use Sahil\PhpQueryOptimizer\QueryAnalyzer;
use Sahil\PhpQueryOptimizer\CodeAnalyzer;

$query = $argv[1] ?? '';

if (!$query) {
    echo "Usage: php query-analyzer.php \"SQL QUERY\"\n";
    exit;
}

$queryAnalyzer  = new QueryAnalyzer();
$queryAnalyzer ->loadDefaultRules();

$codeAnalyzer = new CodeAnalyzer($queryAnalyzer);

$result = $codeAnalyzer->analyzeFile("example.php");

echo "Score: " . $result['score'] . PHP_EOL;
echo "Issues:" . PHP_EOL;

foreach ($result['issues'] as $issue) {
    echo "- " . $issue . PHP_EOL;
}