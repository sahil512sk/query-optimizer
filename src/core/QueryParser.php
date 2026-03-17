<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Core;

class QueryParser
{
    public static function parse(string $query): array
    {
        return [
            'has_where' => stripos($query, 'WHERE') !== false,
            'has_order_by' => stripos($query, 'ORDER BY') !== false,
            'has_limit' => stripos($query, 'LIMIT') !== false,
            'has_join' => stripos($query, 'JOIN') !== false,
            'has_group_by' => stripos($query, 'GROUP BY') !== false
        ];
    }
}