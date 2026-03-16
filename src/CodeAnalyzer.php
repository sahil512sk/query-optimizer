<?php

namespace Sahil\PhpQueryOptimizer;

class CodeAnalyzer
{
    private QueryAnalyzer $queryAnalyzer;

    public function __construct(QueryAnalyzer $queryAnalyzer)
    {
        $this->queryAnalyzer = $queryAnalyzer;
    }

    public function analyzeFile(string $filePath): array
    {
        $code = file_get_contents($filePath);

        $patterns = [
            '/->query\(\s*"([^"]+)"/i',          // PDO
            '/->prepare\(\s*"([^"]+)"/i',        // PDO prepared
            '/mysqli_query\([^,]+,\s*"([^"]+)"/i', // mysqli
            '/DB::select\(\s*"([^"]+)"/i',       // Laravel
            '/DB::statement\(\s*"([^"]+)"/i',    // Laravel
        ];

        $queries = [];

        foreach ($patterns as $pattern) {
            preg_match_all($pattern, $code, $matches);

            foreach ($matches[1] ?? $matches[0] as $query) {
                $queries[] = $query;
            }
        }

        $results = [];

        foreach ($queries as $query) {

            $analysis = $this->queryAnalyzer->analyze($query);

            $results[] = [
                "query" => $query,
                "analysis" => $analysis
            ];
        }

        return $results;
    }
}