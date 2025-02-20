<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = new PhpCsFixer\Finder()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('tests')
    ->exclude('vendor')
;

return new PhpCsFixer\Config()
    ->setRules([
        '@Symfony' => true,
    ])
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setUsingCache(false)
    ->setFinder($finder)
;
