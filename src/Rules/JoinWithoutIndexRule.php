<?php

namespace Sahil\PhpQueryOptimizer\Rules;

class JoinWithoutIndexRule implements RuleInterface
{
    public function check(string $query, array $parsed): ?array
    {
        $hasJoin = preg_match("/JOIN\s+/i", $query);
        $hasOn = preg_match("/\sON\s+/i", $query);

        if ($hasJoin && !$hasOn) {
            return [
                'issue' => "JOIN detected without ON condition. This may cause a cross join and large result sets.",
                'solution' => "Add proper JOIN condition: SELECT * FROM users u JOIN orders o ON u.id = o.user_id"
            ];
        }

        return null;
    }
}