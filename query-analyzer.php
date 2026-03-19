<?php

// Find autoloader
$autoloadCandidates = [
    __DIR__ . '/vendor/autoload.php',                // if running from source
    __DIR__ . '/../../autoload.php',                  // if installed via composer
    dirname(__DIR__, 3) . '/autoload.php',            // if installed deeper
    getcwd() . '/vendor/autoload.php',                // executed from project root
    getcwd() . '/autoload.php',                       // executed from project root directly
];

$autoloadFound = false;
foreach ($autoloadCandidates as $candidate) {
    if (file_exists($candidate)) {
        require $candidate;
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    fwrite(STDERR, "Could not find autoload.php. Searched:\n" . implode("\n", $autoloadCandidates) . "\n");
    exit(1);
}

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