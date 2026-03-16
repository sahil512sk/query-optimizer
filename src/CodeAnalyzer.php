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

        $results = [];

        // Analyze raw SQL queries
        $sqlQueries = $this->extractRawSQL($code);
        foreach ($sqlQueries as $query) {
            $analysis = $this->queryAnalyzer->analyze($query);
            $results[] = [
                "query" => $query,
                "analysis" => $analysis,
                "type" => "Raw SQL"
            ];
        }

        // Analyze Laravel Eloquent patterns
        $eloquentPatterns = $this->analyzeEloquentPatterns($code);
        $results = array_merge($results, $eloquentPatterns);

        return $results;
    }

    private function extractRawSQL(string $code): array
    {
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

        return $queries;
    }

    private function analyzeEloquentPatterns(string $code): array
    {
        $results = [];
        $lines = explode("\n", $code);

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            
            $patterns = [
                'Model::all()' => '/(\w+)::all\(\)/',
                'Model::with()' => '/(\w+)::with\(\[([^\]]+)\]\)/',
                '->leftJoin()' => '/->leftJoin\s*\(\s*[\'"]([^\'"]+)[\'"]/',
                '->join()' => '/->join\s*\(\s*[\'"]([^\'"]+)[\'"]/',
                '->where()' => '/->where\s*\(/',
                '->orWhere()' => '/->orWhere\s*\(/',
                '->whereIn()' => '/->whereIn\s*\(/',
                '->select(*)' => '/->select\s*\(\s*[\'"][^\'"]*\*[^\'"]*[\'"]/',
                '->orderByRaw()' => '/->orderByRaw\s*\(/',
                '->get()' => '/->get\s*\(\s*\)/',
                '->first()' => '/->first\s*\(\s*\)/',
                '::whereHas()' => '/(\w+)::whereHas\s*\(/',
                '::withCount()' => '/(\w+)::withCount\s*\(/',
            ];

            foreach ($patterns as $patternName => $pattern) {
                if (preg_match($pattern, $line, $matches)) {
                    $analysis = $this->analyzeEloquentPattern($patternName, $line, $matches);
                    if ($analysis) {
                        $results[] = [
                            "query" => trim($line),
                            "analysis" => $analysis,
                            "type" => "Laravel Eloquent",
                            "line" => $lineNumber + 1
                        ];
                    }
                    break;
                }
            }
        }

        return $results;
    }

    private function analyzeEloquentPattern(string $pattern, string $line, array $matches): array
    {
        $issues = [];
        $score = 100;

        switch ($pattern) {
            case 'Model::all()':
                $issues[] = [
                    'issue' => 'Using Model::all() loads all records without pagination',
                    'solution' => 'Use pagination: Model::paginate(50) or add WHERE clause: Model::where("active", 1)->get()'
                ];
                $score -= 20;
                break;

            case 'Model::with()':
                $relations = isset($matches[2]) ? $matches[2] : '';
                $relationCount = substr_count($relations, ',') + 1;
                if ($relationCount > 3) {
                    $issues[] = [
                        'issue' => "Loading many relations ($relationCount) may cause N+1 queries",
                        'solution' => 'Consider selective loading or lazy loading for less frequently used relations'
                    ];
                    $score -= 10;
                }
                break;

            case '->leftJoin()':
            case '->join()':
                $table = $matches[1] ?? '';
                $issues[] = [
                    'issue' => "JOIN operation detected on table: $table",
                    'solution' => 'Ensure joined columns have proper indexes and consider using constraints'
                ];
                $score -= 5;
                break;

            case '->select(*)':
                $issues[] = [
                    'issue' => 'Using SELECT * pattern in Eloquent',
                    'solution' => 'Specify exact columns: ->select(["id", "name", "email"])'
                ];
                $score -= 10;
                break;

            case '->orderByRaw()':
                $issues[] = [
                    'issue' => 'Using orderByRaw() may prevent index usage',
                    'solution' => 'Use standard orderBy() when possible or ensure proper indexes'
                ];
                $score -= 10;
                break;

            case '::whereHas()':
                $issues[] = [
                    'issue' => 'whereHas() can be slow on large datasets',
                    'solution' => 'Consider using joins instead or add indexes on foreign key columns'
                ];
                $score -= 10;
                break;

            case '->get()':
                // Check if there's no limit/chunking
                if (!preg_match('/limit|take|chunk/i', $line)) {
                    $issues[] = [
                        'issue' => 'get() without limit may return many records',
                        'solution' => 'Add limit: ->take(100) or use pagination: ->paginate()'
                    ];
                    $score -= 10;
                }
                break;
        }

        return [
            "score" => max($score, 0),
            "issues" => $issues
        ];
    }
}