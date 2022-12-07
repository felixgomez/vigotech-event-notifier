<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude(['vendor'])
    ->in(__DIR__.'/src');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
