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

### After Installation (via Composer)

**Windows Users:**
```bash
# Method 1: Direct PHP execution (Recommended)
php vendor\bin\query-analyze.php path\to\your\file.php
php vendor\bin\query-analyze.php "SELECT * FROM users ORDER BY created_at"

# Method 2: Use package directly
php vendor\professionalchacha\php-query-optimizer\analyze-file.php path\to\file.php
php vendor\professionalchacha\php-query-optimizer\query-analyzer.php "SELECT * FROM users"

# Method 3: Use provided batch file
vendor-bin-query-analyze.bat path\to\your\file.php
vendor-bin-query-analyze.bat "SELECT * FROM users"
```

**Linux/Mac Users:**
```bash
# Method 1: Using vendor/bin (Recommended)
php vendor/bin/query-analyze.php path/to/your/file.php
php vendor/bin/query-analyze.php "SELECT * FROM users ORDER BY created_at"

# Method 2: Use package directly
php vendor/professionalchacha/php-query-optimizer/analyze-file.php path/to/file.php
php vendor/professionalchacha/php-query-optimizer/query-analyzer.php "SELECT * FROM users"
```

**Method 2: Using Composer Script**
```bash
# Add this to your composer.json scripts section:
# "query-analyze": "vendor/bin/query-analyze.php"

# Then run:
composer query-analyze path/to/your/file.php
composer query-analyze "SELECT * FROM users"
```

**Method 3: Create Local Alias**
```bash
# Create a local batch file (Windows)
echo @echo off > query-analyze.bat
echo php vendor\bin\query-analyze.php %%* >> query-analyze.bat

# Or create a local shell script (Linux/Mac)
echo '#!/bin/bash' > query-analyze
echo 'php vendor/bin/query-analyze.php "$@"' >> query-analyze
chmod +x query-analyze

# Then use:
./query-analyze path/to/your/file.php
```

**Method 4: Global Setup (Advanced)**
```bash
# Run setup script (requires admin rights)
php setup-global.bat  # Windows
bash setup-global.sh   # Linux/Mac
```

**Alternative Commands**
```bash
# Windows
php vendor\bin\analyze-file.php path\to\your\file.php
php vendor\bin\query-analyzer.php "SELECT * FROM users"

# Linux/Mac
php vendor/bin/analyze-file.php path/to/your/file.php
php vendor/bin/query-analyzer.php "SELECT * FROM users ORDER BY created_at"
```

### Command Line (from source)

```bash
php query-analyze.php "SELECT * FROM users ORDER BY created_at"
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

## Laravel Integration

### Quick Setup
```bash
composer require professionalchacha/php-query-optimizer
# Auto-discovers the Laravel service provider
php artisan analyze:queries app/Http/Controllers/UserController.php
```

### Usage
```bash
php artisan analyze:queries app/Http/Controllers/UserController.php
php artisan analyze:queries database/seeders/DatabaseSeeder.php
php artisan analyze:queries app/Models/User.php
```

**Features:**
- 🎨 Beautiful, color-coded output with emojis
- 📍 Line numbers and query types
- 📊 Performance scoring with indicators
- 📈 Summary reports
- ⚙️ Configurable rules and thresholds

For detailed setup and configuration, see [LARAVEL_SETUP.md](LARAVEL_SETUP.md).

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