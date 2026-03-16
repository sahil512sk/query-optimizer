<?php

namespace Sahil\PhpQueryOptimizer\Rules;

interface RuleInterface
{
    public function check(string $query, array $parsed): ?array;
}