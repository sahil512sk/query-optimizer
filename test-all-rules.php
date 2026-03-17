<?php

require __DIR__ . '/vendor/autoload.php';

use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;

$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();

$testCases = [
    // Test SelectStarRule
    "SELECT * FROM users",
    
    // Test MissingWhereRule
    "SELECT name FROM users",
    
    // Test OrderByWithoutLimitRule
    "SELECT id FROM users ORDER BY created_at",
    
    // Test LikeWildcardRule
    "SELECT * FROM users WHERE email LIKE '%gmail.com'",
    
    // Test JoinWithoutIndexRule
    "SELECT * FROM users u JOIN orders o ON u.id = o.user_id",
    
    // Test SubqueryRule
    "SELECT * FROM users WHERE id IN (SELECT user_id FROM orders)",
    
    // Test TooManyJoinsRule
    "SELECT * FROM a JOIN b ON a.id = b.a_id JOIN c ON b.id = c.b_id JOIN d ON c.id = d.c_id JOIN e ON d.id = e.d_id"
];

foreach ($testCases as $query) {
    echo "\nTesting: $query\n";
    echo str_repeat("-", 80) . "\n";
    $result = $analyzer->analyze($query);
    echo "Score: {$result['score']}\n";
    echo "Issues: " . count($result['issues']) . "\n";
    
    foreach ($result['issues'] as $issue) {
        if (is_array($issue)) {
            echo "- {$issue['issue']}\n";
            echo "  Solution: {$issue['solution']}\n";
        } else {
            echo "- $issue\n";
        }
    }
    echo "\n";
}
