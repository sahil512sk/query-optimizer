<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class MissingWhereRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        if ($parsed['type'] === 'SELECT' && !$parsed['has_where']) {
            return "SELECT query without WHERE clause may cause full table scan.";
        }

        return null;
    }
}