<?php
/**
 * SaveButton.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Ui\Component\Control
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Ui\Component\Control;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

use function array_replace_recursive;

class SaveButton implements ButtonProviderInterface
{
    /** @var array $components */
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
        $this->components = array_replace_recursive(
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
}
