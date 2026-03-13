<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class SubqueryRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        if (preg_match('/\(\s*SELECT\b/i', $query)) {
            return "Subquery detected. Consider using JOIN if possible for better performance.";
        }

        return null;
    }
}