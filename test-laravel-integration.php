<?php

// Test Laravel command structure
require __DIR__ . '/vendor/autoload.php';

// Check if the command class exists
$commandClass = 'ProfessionalChacha\PhpQueryOptimizer\Laravel\Commands\AnalyzeQueriesCommand';

if (class_exists($commandClass)) {
    echo "✅ Command class exists: $commandClass\n";
    
    // Check namespace
    $reflection = new ReflectionClass($commandClass);
    echo "✅ Namespace: " . $reflection->getNamespaceName() . "\n";
    echo "✅ Class: " . $reflection->getShortName() . "\n";
    
    // Check if it extends Command
    if ($reflection->isSubclassOf('Illuminate\Console\Command')) {
        echo "✅ Extends Illuminate\Console\Command\n";
    } else {
        echo "❌ Does not extend Illuminate\Console\Command\n";
    }
    
    // Check signature
    $instance = new $commandClass();
    if (property_exists($instance, 'signature')) {
        echo "✅ Signature: " . $instance->signature . "\n";
    }
    
} else {
    echo "❌ Command class not found: $commandClass\n";
}

// Check service provider
$providerClass = 'ProfessionalChacha\PhpQueryOptimizer\Laravel\LaravelServiceProvider';
if (class_exists($providerClass)) {
    echo "✅ Service Provider class exists: $providerClass\n";
} else {
    echo "❌ Service Provider class not found: $providerClass\n";
}

echo "\n📋 Autoload paths checked:\n";
echo "- src/vendor/autoload.php\n";
echo "- ../../autoload.php\n";
echo "- dirname(__DIR__, 3) . '/autoload.php'\n";
echo "- getcwd() . '/vendor/autoload.php'\n";
echo "- getcwd() . '/autoload.php'\n";
