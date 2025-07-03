<?php

use Symplify\EasyCodingStandard\Config\ECSConfig;

/**
 * @phpstan-ignore-next-line
 */
return static function(ECSConfig $config): void {
    $config->import(__DIR__ . '/vendor/buckhamduffy/coding-standards/ecs.php');
    $config->indentation('spaces');
    $config->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
};
