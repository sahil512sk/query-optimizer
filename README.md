# PHP Query Optimizer

A PHP library for analyzing SQL queries and identifying potential performance issues.

## Features

- Analyzes SQL queries for common performance anti-patterns
- Provides suggestions for query optimization
- Extensible rule system
- Command-line interface for quick analysis

## Installation

```bash
composer require professionalchacha/php-query-optimizer
```

Or clone the repository:

```bash
git clone https://github.com/professionalchacha/php-query-optimizer.git
cd php-query-optimizer
composer install
```

## Usage

### Command Line

```bash
php query-analyzer.php "SELECT * FROM users ORDER BY created_at"
```

### Analyze PHP Files

```bash
php analyze-file.php path/to/your/file.php
```

### Programmatic

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use ProfessionalChacha\PhpQueryOptimizer\QueryAnalyzer;

$analyzer = new QueryAnalyzer();
$analyzer->loadDefaultRules();

$result = $analyzer->analyze("SELECT * FROM users");
print_r($result);
```

## Available Rules

- **SelectStarRule**: Detects SELECT * usage
- **OrderByWithoutLimitRule**: Detects ORDER BY without LIMIT
- **LikeWildcardRule**: Detects leading wildcards in LIKE clauses
- **MissingWhereRule**: Detects queries without WHERE clauses
- **JoinWithoutIndexRule**: Detects potential unindexed joins

## Testing

Run the test suite:

```bash
php test.php
```

## License

MIT