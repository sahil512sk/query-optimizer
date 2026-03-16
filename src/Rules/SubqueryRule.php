<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class SubqueryRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        if (preg_match('/\(\s*SELECT\b/i', $query)) {
            return [
                'issue' => "Subquery detected. Consider using JOIN if possible for better performance.",
                'solution' => "Replace subquery with JOIN: SELECT u.* FROM users u JOIN orders o ON u.id = o.user_id"
            ];
        }

        return null;
    }
}