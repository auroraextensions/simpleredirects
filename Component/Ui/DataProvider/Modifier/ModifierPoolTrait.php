<?php
/**
 * ModifierPoolTrait.php
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

namespace AuroraExtensions\SimpleRedirects\Component\Ui\DataProvider\Modifier;

use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * @api
 * @since 1.0.0
 */
trait ModifierPoolTrait
{
    /** @property PoolInterface $modifierPool */
    private $modifierPool;

    /**
     * @return PoolInterface
     */
    public function getModifierPool(): PoolInterface
    {
        return $this->modifierPool;
    }

    /**
     * @return ModifierInterface[]
     */
    public function getModifiers(): array
    {
        return $this->getModifierPool()
            ->getModifiersInstances();
    }
}
