<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Rules;

class SelectStarRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        if (!$parsed['has_where'] && $parsed['type'] === 'SELECT' && preg_match('/SELECT\s+\*/i', $query))
        {
            return [
                'issue' => "Avoid using SELECT *. Specify required columns.",
                'solution' => "Replace SELECT * with specific column names: SELECT id, name, email FROM users"
            ];
        }

        return null;
    }
}