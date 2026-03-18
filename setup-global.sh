#!/bin/bash

# Setup script to make query-analyze available globally
# Run this after composer require professionalchacha/php-query-optimizer

echo "Setting up global query-analyze command..."

# Find the vendor bin directory
VENDOR_BIN=$(composer config bin-dir --absolute 2>/dev/null)
if [ -z "$VENDOR_BIN" ]; then
    VENDOR_BIN="vendor/bin"
fi

# Check if query-analyze exists
if [ ! -f "$VENDOR_BIN/query-analyze" ]; then
    echo "Error: query-analyze not found in $VENDOR_BIN"
    echo "Make sure you have installed: composer require professionalchacha/php-query-optimizer"
    exit 1
fi

# Create global symlink (requires sudo)
echo "Creating global symlink..."
sudo ln -sf "$(pwd)/$VENDOR_BIN/query-analyze" /usr/local/bin/query-analyze

if [ $? -eq 0 ]; then
    echo "✅ Global command 'query-analyze' is now available!"
    echo "Usage: query-analyze path/to/file.php"
    echo "       query-analyze \"SELECT * FROM users\""
else
    echo "❌ Failed to create global symlink"
    echo "You can still use: $VENDOR_BIN/query-analyze"
fi
