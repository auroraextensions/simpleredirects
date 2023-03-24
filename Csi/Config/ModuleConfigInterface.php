<?php
/**
 * ModuleConfigInterface.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Csi\Config
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Csi\Config;

interface ModuleConfigInterface
{
    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function isModuleEnabled(int $store, string $scope): bool;
}
