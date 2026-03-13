<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class TooManyJoinsRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        preg_match_all('/\bJOIN\b/i', $query, $matches);

        $joinCount = count($matches[0]);

        if ($joinCount > 3) {
            return "Query contains many JOINs ($joinCount). Consider simplifying joins or optimizing indexes.";
        }

        return null;
    }
}