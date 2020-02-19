<?php
/**
 * SaveButton.php
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

namespace AuroraExtensions\SimpleRedirects\Ui\Component\Control;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /** @property array $components */
    private $components = [
        'button' => ['event' => 'save'],
    ];

    /**
     * @param array $components
     * @return void
     */
    public function __construct(
        array $components = []
    ) {
        $this->components = $this->getMergeData(
            $this->components,
            $components
        );
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'class' => 'save primary',
            'data_attribute' => [
                'form-role' => 'save',
                'mage-init' => $this->components,
            ],
            'label' => __('Save'),
            'on_click' => '',
            'sort_order' => 10,
        ];
    }

    /**
     * @param array[] ...$components
     * @return array
     */
    private function getMergeData(array ...$components): array
    {
        /** @var array $result */
        $result = [];

        /** @var array $component */
        foreach ($components as $component) {
            $result = array_replace_recursive(
                $result,
                $component
            );
        }

        return $result;
    }
}
