<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/tests')
    ->exclude('bootstrap/cache')
    ->exclude('storage')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'concat_space' => ['spacing' => 'one'],
        'blank_line_before_statement' => [
            'statements' => ['return'],
        ],
    ])
    ->setFinder($finder);
