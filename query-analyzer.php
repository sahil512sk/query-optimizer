<?php

require __DIR__ . '/vendor/autoload.php';

use Sahil\PhpQueryOptimizer\QueryAnalyzer;

$query = $argv[1] ?? '';

if (!$query) {
    echo "Usage: php query-analyzer.php \"SQL QUERY\"\n";
    exit;
}

$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();

$result = $analyzer->analyze($query);

echo "Score: " . $result['score'] . PHP_EOL;
echo "Issues:" . PHP_EOL;

foreach ($result['issues'] as $issue) {
    echo "- " . $issue . PHP_EOL;
}