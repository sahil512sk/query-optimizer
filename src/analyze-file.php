#!/usr/bin/env php
<?php

// Find autoloader
$autoloadCandidates = [
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

// composer require professionalchacha/php-query-optimizer

use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;
use ProfessionalChacha\PhpQueryOptimizer\CodeAnalyzer;

$file = $argv[1] ?? null;

if (!$file) {
    echo "Usage: analyze-file <file.php>\n";
    exit;
}

if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit;
}

$queryAnalyzer = new QueryAnalyzer();
$queryAnalyzer->loadDefaultRules();

$codeAnalyzer = new CodeAnalyzer($queryAnalyzer);

$result = $codeAnalyzer->analyzeFile($file);

foreach ($result as $item) {

    echo "\nQuery:\n";
    echo $item['query'] . "\n";

    if (isset($item['type'])) {
        echo "Type: " . $item['type'] . "\n";
    }
    
    if (isset($item['line'])) {
        echo "Line: " . $item['line'] . "\n";
    }

    echo "Score: " . $item['analysis']['score'] . "\n";

    echo "Issues:\n";

    foreach ($item['analysis']['issues'] as $issue) {
        if (is_array($issue)) {
            echo "- " . $issue['issue'] . "\n";
            echo "  Solution: " . $issue['solution'] . "\n";
        } else {
            echo "- " . $issue . "\n";
        }
    }

    echo "----------------------\n";
}
