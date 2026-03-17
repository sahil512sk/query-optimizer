<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Rules;

class OrderByWithoutLimitRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        if ($parsed['has_order_by'] && !$parsed['has_limit']) {
            return [
                'issue' => "ORDER BY without LIMIT may cause heavy sorting on large datasets.",
                'solution' => "Add a LIMIT clause: SELECT * FROM users ORDER BY created_at DESC LIMIT 10"
            ];
        }

        return null;
    }
}