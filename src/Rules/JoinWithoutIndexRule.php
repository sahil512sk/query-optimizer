<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class JoinWithoutIndexRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?string
    {
        $hasJoin = preg_match("/JOIN\s+/i", $query);
        $hasOn = preg_match("/\sON\s+/i", $query);

        if ($hasJoin && !$hasOn) {
            return "JOIN detected without ON condition. This may cause a cross join and large result sets.";
        }

        return null;
    }
}