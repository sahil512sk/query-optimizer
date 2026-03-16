<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class TooManyJoinsRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        preg_match_all('/\bJOIN\b/i', $query, $matches);

        $joinCount = count($matches[0]);

        if ($joinCount > 3) {
            return [
                'issue' => "Query contains many JOINs ($joinCount). Consider simplifying joins or optimizing indexes.",
                'solution' => "Break complex query into simpler queries or ensure all JOIN columns have proper indexes"
            ];
        }

        return null;
    }
}