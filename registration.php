<?php
/**
 * registration.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'AuroraExtensions_SimpleRedirects',
    __DIR__
);
