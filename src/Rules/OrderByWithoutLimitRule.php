<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class OrderByWithoutLimitRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        if ($parsed['has_order_by'] && !$parsed['has_limit']) {
            return "ORDER BY without LIMIT may cause heavy sorting on large datasets.";
        }

        return null;
    }
}