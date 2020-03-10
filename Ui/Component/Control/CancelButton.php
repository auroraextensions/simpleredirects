<?php
/**
 * CancelButton.php
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

use Magento\Framework\{
    UrlInterface,
    View\Element\UiComponent\Control\ButtonProviderInterface
};

class CancelButton implements ButtonProviderInterface
{
    /** @property string $route */
    private $route;

    /** @property UrlInterface $urlBuilder */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     * @param string|null $route
     * @return void
     */
    public function __construct(
        UrlInterface $urlBuilder,
        string $route = null
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->route = $route ?? '';
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'class' => 'cancel secondary',
            'label' => __('Cancel'),
            'on_click' => $this->getOnClickJs(),
            'sort_order' => 10,
        ];
    }

    /**
     * @return string
     */
    private function getOnClickJs(): string
    {
        /** @var string $targetUrl */
        $targetUrl = $this->urlBuilder->getUrl(
            $this->route,
            [
                '_secure' => true,
            ]
        );

        return "(function(){window.location='{$targetUrl}';})();";
    }
}
