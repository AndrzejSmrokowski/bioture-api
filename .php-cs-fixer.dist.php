<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude([
        'var',
        'vendor',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return new Config()
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/var/cache/php-cs-fixer/.php-cs-fixer.cache')
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'array_indentation' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
    ])
    ->setFinder($finder);
