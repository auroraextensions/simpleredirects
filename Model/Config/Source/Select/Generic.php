<?php
/**
 * Generic.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Config\Source\Select;

use AuroraExtensions\SimpleRedirects\Csi\Config\ModuleConfigInterface;
use Magento\Framework\Option\ArrayInterface;

class Generic implements ArrayInterface
{
    /** @property array $options */
    private $options = [];

    /**
     * @param ModuleConfigInterface $moduleConfig
     * @param string $key
     * @param bool $flip
     * @return void
     */
    public function __construct(
        ModuleConfigInterface $moduleConfig,
        string $key,
        bool $flip = true
    ) {
        /** @var array $data */
        $data = $moduleConfig->getContainer()
            ->getData($key) ?? [];

        /** @var array $sort */
        $sort = $flip ? array_flip($data) : $data;

        array_walk(
            $sort,
            [
                $this,
                'setOption'
            ]
        );
    }

    /**
     * @param int|string|null $value
     * @param int|string $key
     * @return void
     */
    private function setOption($value, $key): void
    {
        $this->options[] = [
            'label' => __($key),
            'value' => $value,
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->options;
    }
}
