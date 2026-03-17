<?php

namespace ProfessionalChacha\PhpQueryOptimizer\Core;

class QueryTypeDetector
{
    public static function detect(string $query): string
    {
        $query = trim($query);

        if (stripos($query, 'SELECT') === 0) {
            return 'SELECT';
        }

        if (stripos($query, 'UPDATE') === 0) {
            return 'UPDATE';
        }

        if (stripos($query, 'DELETE') === 0) {
            return 'DELETE';
        }

        if (stripos($query, 'INSERT') === 0) {
            return 'INSERT';
        }

        return 'UNKNOWN';
    }
}