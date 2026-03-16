<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class MissingWhereRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        if ($parsed['type'] === 'SELECT' && !$parsed['has_where']) {
            return [
                'issue' => "SELECT query without WHERE clause may cause full table scan.",
                'solution' => "Add a WHERE clause to filter results: SELECT * FROM users WHERE active = 1"
            ];
        }

        return null;
    }
}