# Laravel Integration Guide

## Installation in Laravel

1. **Install the package:**
   ```bash
   composer require professionalchacha/php-query-optimizer
   ```

2. **Publish the configuration (optional):**
   ```bash
   php artisan vendor:publish --provider="ProfessionalChacha\\PhpQueryOptimizer\\Laravel\\LaravelServiceProvider"
   ```

3. **The service provider will be auto-discovered and the command will be available.**

## Usage

### Analyze a PHP file:
```bash
php artisan analyze:queries app/Http/Controllers/UserController.php
php artisan analyze:queries database/seeders/DatabaseSeeder.php
php artisan analyze:queries app/Models/User.php
```

### Example Output:
```
Analyzing queries in: app/Http/Controllers/UserController.php
--------------------------------------------------------------------------------

🔍 Query:
SELECT * FROM users ORDER BY created_at
📝 Type: Raw SQL
📍 Line: 15
📊 Score: ⚠️  70
⚠️  Issues:
  • SELECT query without WHERE clause may cause full table scan.
    💡 Solution: Add a WHERE clause to filter results: SELECT * FROM users WHERE active = 1
  • ORDER BY without LIMIT may cause heavy sorting on large datasets.
    💡 Solution: Add a LIMIT clause: SELECT * FROM users ORDER BY created_at DESC LIMIT 10
  • Avoid using SELECT *. Specify required columns.
    💡 Solution: Replace SELECT * with specific column names: SELECT id, name, email FROM users
----------------------------------------

📈 Summary:
  Total Queries: 1
  Total Issues: 3
  Overall Score: ⚠️  70/100 (Fair)

💡 Consider optimizing the queries marked with issues to improve performance.
```

## Features

- **Auto-discovery**: Laravel automatically registers the command
- **Beautiful Output**: Color-coded results with emojis for better readability
- **Detailed Analysis**: Shows line numbers, query types, and optimization suggestions
- **Performance Scoring**: Overall score with color indicators
- **Summary Report**: Total queries, issues, and recommendations

## Configuration

After publishing the config file, you can customize:

```php
// config/query-analyzer.php
return [
    'enabled' => true,
    'rules' => [
        'select_star' => true,
        'missing_where' => true,
        'order_by_without_limit' => true,
        'like_wildcard' => true,
        'join_without_index' => true,
        'subquery' => true,
        'too_many_joins' => true,
    ],
    'thresholds' => [
        'too_many_joins' => 4,
        'score_warning' => 70,
        'score_danger' => 50,
    ],
];
```

## Integration with CI/CD

Add to your Laravel application's testing pipeline:

```bash
# Analyze all controllers
php artisan analyze:queries app/Http/Controllers/

# Analyze all models
php artisan analyze:queries app/Models/

# Exit with error code if issues found
php artisan analyze:queries app/Http/Controllers/ || exit 1
```

## Programmatic Usage

```php
use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;

$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();

$result = $analyzer->analyze("SELECT * FROM users");
// Use $result in your application
```
