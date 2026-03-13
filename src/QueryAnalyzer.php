<?php

namespace Sahil\PhpQueryOptimizer;

use Sahil\PhpQueryOptimizer\Rules\JoinWithoutIndexRule;
use Sahil\PhpQueryOptimizer\Rules\LikeWildcardRule;
use Sahil\PhpQueryOptimizer\Rules\MissingWhereRule;
use Sahil\PhpQueryOptimizer\Rules\OrderByWithoutLimitRule;
use Sahil\PhpQueryOptimizer\Rules\SelectStarRule;

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

        $parsed = [
            'type' => strtoupper(strtok(trim($query), " ")),
            'has_where' => stripos($query, 'WHERE') !== false,
            'has_order_by' => stripos($query, 'ORDER BY') !== false,
            'has_limit' => stripos($query, 'LIMIT') !== false,
            'has_join' => stripos($query, 'JOIN') !== false
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