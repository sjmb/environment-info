<?php
/**
 * @copyright Copyright (c) 2026 SJMB (http://sjmb.pl)
 */
declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Sjmb_EnvironmentInfo',
    __DIR__
);
