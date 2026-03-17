<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Rules;

class LikeWildcardRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        if ($parsed['type'] === 'SELECT' && preg_match("/LIKE\s+['\"]%/i", $query))
        {
            return [
                'issue' => "Avoid leading wildcard in LIKE clause. It prevents index usage.",
                'solution' => "Use trailing wildcard instead: WHERE email LIKE 'gmail.com%' or use full-text search"
            ];
        }

        return null;
    }
}