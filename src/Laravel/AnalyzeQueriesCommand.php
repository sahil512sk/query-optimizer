<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Laravel;

use Illuminate\Console\Command;
use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;
use ProfessionalChacha\PhpQueryOptimizer\CodeAnalyzer;

class AnalyzeQueriesCommand extends Command
{
    protected $signature = 'analyze:queries {filename : The PHP file to analyze}';
    protected $description = 'Analyze SQL queries in a PHP file for performance issues';

    public function handle()
    {
        $filename = $this->argument('filename');
        
        if (!file_exists($filename)) {
            $this->error("File not found: {$filename}");
            return 1;
        }

        $this->info("Analyzing queries in: {$filename}");
        $this->line(str_repeat('-', 80));

        try {
            $queryAnalyzer = new QueryAnalyzer();
            $queryAnalyzer->loadDefaultRules();
            
            $codeAnalyzer = new CodeAnalyzer($queryAnalyzer);
            $result = $codeAnalyzer->analyzeFile($filename);

            if (empty($result)) {
                $this->info("No SQL queries found in the file.");
                return 0;
            }

            $totalQueries = count($result);
            $totalIssues = 0;
            $worstScore = 100;

            foreach ($result as $item) {
                $issues = $item['analysis']['issues'] ?? [];
                $score = $item['analysis']['score'] ?? 100;
                $totalIssues += count($issues);
                $worstScore = min($worstScore, $score);

                $this->line("\n🔍 Query:");
                $this->comment($item['query']);

                if (isset($item['type'])) {
                    $this->line("📝 Type: " . $item['type']);
                }
                
                if (isset($item['line'])) {
                    $this->line("📍 Line: " . $item['line']);
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
                $this->line("📊 Score: {$scoreText}");

                if (!empty($issues)) {
                    $this->line("⚠️  Issues:");
                    foreach ($issues as $issue) {
                        if (is_array($issue)) {
                            $this->line("  • " . $issue['issue']);
                            $this->line("    💡 Solution: " . $issue['solution']);
                        } else {
                            $this->line("  • " . $issue);
                        }
                    }
                }

                $this->line(str_repeat('-', 40));
            }

            // Summary
            $this->line("\n📈 Summary:");
            $this->line("  Total Queries: {$totalQueries}");
            $this->line("  Total Issues: {$totalIssues}");
            
            $summaryScore = $worstScore;
            if ($summaryScore >= 90) {
                $this->info("  Overall Score: ✅ {$summaryScore}/100 (Good)");
            } elseif ($summaryScore >= 70) {
                $this->warn("  Overall Score: ⚠️  {$summaryScore}/100 (Fair)");
            } else {
                $this->error("  Overall Score: ❌ {$summaryScore}/100 (Poor)");
            }

            if ($totalIssues > 0) {
                $this->line("\n💡 Consider optimizing the queries marked with issues to improve performance.");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Error analyzing file: " . $e->getMessage());
            return 1;
        }
    }
}
