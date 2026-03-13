<?php

require __DIR__ . '/vendor/autoload.php';

use Sahil\PhpQueryOptimizer\QueryAnalyzer;
use Sahil\PhpQueryOptimizer\Rules\SelectStarRule;
use Sahil\PhpQueryOptimizer\Rules\OrderByWithoutLimitRule;
use Sahil\PhpQueryOptimizer\Rules\LikeWildcardRule;
use Sahil\PhpQueryOptimizer\Rules\MissingWhereRule;
use Sahil\PhpQueryOptimizer\Rules\JoinWithoutIndexRule;

$query = "SELECT * FROM users ORDER BY created_at";
$queries = [
    "SELECT * FROM users",
    "SELECT name FROM users WHERE id = 1",
    "SELECT * FROM users ORDER BY created_at",
    "SELECT * FROM users WHERE email LIKE '%gmail.com'"
];
$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();
foreach ($queries as $query) {
    echo "Query: $query\n";

    $result = $analyzer->analyze($query);

    print_r($result);
    echo "----------------------\n";
}

$analyzer->addRule(new SelectStarRule());
$analyzer->addRule(new OrderByWithoutLimitRule());
$analyzer->addRule(new LikeWildcardRule());
$analyzer->addRule(new JoinWithoutIndexRule());
$analyzer->addRule(new MissingWhereRule());

$result = $analyzer->analyze($query);

print_r($result);