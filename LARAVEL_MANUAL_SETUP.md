# Laravel Manual Setup Guide

## Quick Installation (Recommended)

### Option 1: Manual Registration (Works in any Laravel version)

1. **Install the package:**
   ```bash
   composer require professionalchacha/php-query-optimizer
   ```

2. **Register the command manually** in `app/Console/Kernel.php`:
   ```php
   protected $commands = [
       \ProfessionalChacha\PhpQueryOptimizer\Laravel\Commands\AnalyzeQueriesCommand::class,
   ];
   ```

3. **Use the command:**
   ```bash
   php artisan analyze:queries app/Http/Controllers/UserController.php
   ```

### Option 2: Service Provider (Laravel 5.5+)

1. **Install the package** (same as above)

2. **Add to config/app.php providers array:**
   ```php
   'providers' => [
       // ...
       \ProfessionalChacha\PhpQueryOptimizer\Laravel\LaravelServiceProvider::class,
   ],
   ```

3. **Use the command** (same as above)

## Troubleshooting

### If you get "There are no commands defined in the 'analyze' namespace":

**Solution 1: Clear Laravel cache**
```bash
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

**Solution 2: Check class exists**
```bash
php artisan tinker
>>> class_exists(\ProfessionalChacha\PhpQueryOptimizer\Laravel\Commands\AnalyzeQueriesCommand::class)
```

**Solution 3: Manual registration (most reliable)**
Add the command directly to `app/Console/Kernel.php` as shown in Option 1.

## Usage Examples

```bash
# Analyze specific file
php artisan analyze:queries app/Http/Controllers/UserController.php

# Analyze all controllers
php artisan analyze:queries app/Http/Controllers/

# Analyze models
php artisan analyze:queries app/Models/User.php

# Use in CI/CD
php artisan analyze:queries app/Http/Controllers/ || exit 1
```

## Expected Output

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
    💡 Solution: Add a WHERE clause to filter results
  • ORDER BY without LIMIT may cause heavy sorting on large datasets.
    💡 Solution: Add a LIMIT clause
  • Avoid using SELECT *. Specify required columns.
    💡 Solution: Replace SELECT * with specific column names
----------------------------------------

📈 Summary:
  Total Queries: 1
  Total Issues: 3
  Overall Score: ⚠️  70/100 (Fair)

💡 Consider optimizing queries marked with issues to improve performance.
```

## Why Manual Registration?

Laravel's package auto-discovery can be unreliable in some environments. Manual registration in `app/Console/Kernel.php` ensures the command is always available regardless of:
- Laravel version
- Package discovery configuration
- Cache issues
- Autoloader problems
