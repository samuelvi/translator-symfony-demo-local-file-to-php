<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets(php84: true)
    ->withPreparedSets(
        codeQuality: true,
        deadCode: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
        codingStyle: true,
    )
    ->withSets([
        // Symfony upgrade rules
        SymfonySetList::SYMFONY_71,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,

        // Doctrine rules
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ])
    ->withImportNames(
        importNames: true,
        importDocBlockNames: true,
        removeUnusedImports: true,
    )
    ->withParallel()
    ->withCache(
        cacheDirectory: __DIR__ . '/var/cache/rector',
    );
