<?php

namespace Sahil\PhpQueryOptimizer;

use Sahil\PhpQueryOptimizer\Rules\JoinWithoutIndexRule;
use Sahil\PhpQueryOptimizer\Rules\LikeWildcardRule;
use Sahil\PhpQueryOptimizer\Rules\MissingWhereRule;
use Sahil\PhpQueryOptimizer\Rules\OrderByWithoutLimitRule;
use Sahil\PhpQueryOptimizer\Rules\SelectStarRule;
use PHPSQLParser\PHPSQLParser;

class QueryAnalyzer
{
    private array $rules = [];

    public function addRule($rule)
    {
        $this->rules[] = $rule;
    }

    public function analyze(string $query): array
    {
        $issues = [];
        $score = 100;

        $parser = new PHPSQLParser();
        $parsedQuery = $parser->parse($query);

        $parsed = [
            'type' => strtoupper(strtok(trim($query), " ")),
            'has_where' => isset($parsedQuery['WHERE']),
            'has_order_by' => isset($parsedQuery['ORDER']),
            'has_limit' => isset($parsedQuery['LIMIT']),
            'has_join' => isset($parsedQuery['JOIN']),
            'ast' => $parsedQuery
        ];

        foreach ($this->rules as $rule) {

            $result = $rule->check($query, $parsed);

            if ($result) {
                $issues[] = $result;
                $score -= 10;
            }
        }

        return [
            "score" => max($score, 0),
            "issues" => $issues
        ];
    }
    public function loadDefaultRules(): void
    {
        $rulesPath = __DIR__ . '/Rules';

        foreach (glob($rulesPath . '/*.php') as $file) {

            $class = 'Sahil\\PhpQueryOptimizer\\Rules\\' . basename($file, '.php');

            if (class_exists($class)) {

                $rule = new $class();

                if ($rule instanceof \Sahil\PhpQueryOptimizer\Rules\RuleInterface) {
                    $this->rules[] = $rule;
                }
            }
        }
    }
}