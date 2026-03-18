# Publishing to Packagist

## Prerequisites

1. **GitHub Repository**: Push the code to a public GitHub repository
2. **Packagist Account**: Create an account at https://packagist.org
3. **Composer.json**: Ensure composer.json is properly configured

## Steps to Publish

### 1. Push to GitHub

```bash
git add .
git commit -m "Ready for Packagist publishing"
git push origin main
```

### 2. Submit to Packagist

1. Go to https://packagist.org/packages/submit
2. Enter your GitHub repository URL: `https://github.com/professionalchacha/php-query-optimizer`
3. Click "Check" and then "Submit"

### 3. Set up GitHub Hook (Optional but Recommended)

1. In your Packagist package page, click "Set up GitHub Service Hook"
2. This will automatically update the package when you push updates

## After Publishing

Once published, users can install the package:

```bash
composer require professionalchacha/php-query-optimizer
```

And use the commands:

```bash
# Analyze a file
vendor/bin/analyze-file path/to/file.php

# Analyze a query
vendor/bin/query-analyzer "SELECT * FROM users"
```

## Version Management

Update version in composer.json and tag releases:

```bash
# Update version in composer.json to 1.0.1
git add composer.json
git commit -m "Bump version to 1.0.1"
git tag v1.0.1
git push origin v1.0.1
git push origin main
```

## Current Status

- ✅ Package structure ready
- ✅ Binary scripts configured
- ✅ Autoloader detection robust
- ✅ Documentation complete
- ⏳ Ready for Packagist submission

## Testing Before Publishing

Test the package installation locally:

```bash
# Create a test project
mkdir test-project
cd test-project
composer init

# Install local package (for testing)
composer config repositories.local '{"type": "path", "url": "../php-query-optimizer"}'
composer require professionalchacha/php-query-optimizer:@dev

# Test the commands
vendor/bin/analyze-file ../php-query-optimizer/test-sql.php
vendor/bin/query-analyzer "SELECT * FROM users"
```
