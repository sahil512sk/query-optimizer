<?php

// Simulate Laravel environment for testing
require __DIR__ . '/vendor/autoload.php';

// Mock Laravel's Command class
class MockCommand {
    protected $signature = 'analyze:queries {filename}';
    protected $description = 'Analyze SQL queries in a PHP file';
    
    public function argument($key) {
        return $GLOBALS['argv'][2] ?? 'test-sql.php';
    }
    
    public function info($message) {
        echo "\033[32m$message\033[0m\n";
    }
    
    public function error($message) {
        echo "\033[31m$message\033[0m\n";
    }
    
    public function warn($message) {
        echo "\033[33m$message\033[0m\n";
    }
    
    public function comment($message) {
        echo "\033[36m$message\033[0m\n";
    }
    
    public function line($message) {
        echo "$message\n";
    }
}

// Test the command logic
use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;
use ProfessionalChacha\PhpQueryOptimizer\CodeAnalyzer;

$command = new MockCommand();
$filename = $command->argument('filename');

if (!file_exists($filename)) {
    $command->error("File not found: {$filename}");
    exit(1);
}

$command->info("Analyzing queries in: {$filename}");
$command->line(str_repeat('-', 80));

try {
    $queryAnalyzer = new QueryAnalyzer();
    $queryAnalyzer->loadDefaultRules();
    
    $codeAnalyzer = new CodeAnalyzer($queryAnalyzer);
    $result = $codeAnalyzer->analyzeFile($filename);

    if (empty($result)) {
        $command->info("No SQL queries found in the file.");
        exit(0);
    }

    $totalQueries = count($result);
    $totalIssues = 0;
    $worstScore = 100;

    foreach ($result as $item) {
        $issues = $item['analysis']['issues'] ?? [];
        $score = $item['analysis']['score'] ?? 100;
        $totalIssues += count($issues);
        $worstScore = min($worstScore, $score);

        $command->line("\n🔍 Query:");
        $command->comment($item['query']);

        if (isset($item['type'])) {
            $command->line("📝 Type: " . $item['type']);
        }
        
        if (isset($item['line'])) {
            $command->line("📍 Line: " . $item['line']);
        }

        // Color code the score
        $scoreText = $score;
        if ($score >= 90) {
            $scoreText = "✅ {$score}";
        } elseif ($score >= 70) {
            $scoreText = "⚠️  {$score}";
        } else {
            $scoreText = "❌ {$score}";
        }
        $command->line("📊 Score: {$scoreText}");

        if (!empty($issues)) {
            $command->line("⚠️  Issues:");
            foreach ($issues as $issue) {
                if (is_array($issue)) {
                    $command->line("  • " . $issue['issue']);
                    $command->line("    💡 Solution: " . $issue['solution']);
                } else {
                    $command->line("  • " . $issue);
                }
            }
        }

        $command->line(str_repeat('-', 40));
    }

    // Summary
    $command->line("\n📈 Summary:");
    $command->line("  Total Queries: {$totalQueries}");
    $command->line("  Total Issues: {$totalIssues}");
    
    $summaryScore = $worstScore;
    if ($summaryScore >= 90) {
        $command->info("  Overall Score: ✅ {$summaryScore}/100 (Good)");
    } elseif ($summaryScore >= 70) {
        $command->warn("  Overall Score: ⚠️  {$summaryScore}/100 (Fair)");
    } else {
        $command->error("  Overall Score: ❌ {$summaryScore}/100 (Poor)");
    }

    if ($totalIssues > 0) {
        $command->line("\n💡 Consider optimizing the queries marked with issues to improve performance.");
    }

} catch (Exception $e) {
    $command->error("Error analyzing file: " . $e->getMessage());
    exit(1);
}
