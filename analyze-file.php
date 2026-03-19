<?php

require __DIR__ . '/vendor/professionalchacha/php-query-optimizer/analyze-file.php';

// composer require professionalchacha/php-query-optimizer

use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;
use ProfessionalChacha\PhpQueryOptimizer\CodeAnalyzer;

$file = $argv[1] ?? null;
if (!$file) {
    echo "Usage: php analyze-file.php <file.php>\n";
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