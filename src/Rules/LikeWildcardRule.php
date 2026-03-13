<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class LikeWildcardRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        if ($parsed['type'] === 'SELECT' && !$parsed['has_where'] && preg_match("/LIKE\s+['\"]%/i", $query))
        {
            return "Avoid leading wildcard in LIKE clause. It prevents index usage.";
        }

        return null;
    }
}