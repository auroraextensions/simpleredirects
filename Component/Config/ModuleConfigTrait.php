<?php
/**
 * ModuleConfigTrait.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package       AuroraExtensions_SimpleRedirects
 * @copyright     Copyright (C) 2020 Aurora Extensions <support@auroraextensions.com>
 * @license       MIT License
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Component\Config;

trait ModuleConfigTrait
{
    /** @property ModuleConfigInterface $moduleConfig */
    private $moduleConfig;

    /**
     * @return bool
     */
    private function isModuleEnabled(): bool
    {
        return $this->moduleConfig
            ->isModuleEnabled();
    }
}
