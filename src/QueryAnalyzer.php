<?php

namespace ProfessionalChacha\PhpQueryOptimizer;

use ProfessionalChacha\PhpQueryOptimizer\Rules\JoinWithoutIndexRule;
use ProfessionalChacha\PhpQueryOptimizer\Rules\LikeWildcardRule;
use ProfessionalChacha\PhpQueryOptimizer\Rules\MissingWhereRule;
use ProfessionalChacha\PhpQueryOptimizer\Rules\OrderByWithoutLimitRule;
use ProfessionalChacha\PhpQueryOptimizer\Rules\SelectStarRule;
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

            $class = 'ProfessionalChacha\\PhpQueryOptimizer\\Rules\\' . basename($file, '.php');

            if (class_exists($class)) {

                $rule = new $class();

                if ($rule instanceof \ProfessionalChacha\PhpQueryOptimizer\Rules\RuleInterface) {
                    $this->rules[] = $rule;
                }
            }
        }
    }
}