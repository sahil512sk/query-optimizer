<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class SelectStarRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        if (!$parsed['has_where'] && $parsed['type'] === 'SELECT' && preg_match('/SELECT\s+\*/i', $query))
        {
            return "Avoid using SELECT *. Specify required columns.";
        }

        return null;
    }
}