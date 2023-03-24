<?php
/**
 * CancelButton.php
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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class CancelButton implements ButtonProviderInterface
{
    /** @var string $route */
    private $route;

    /** @var UrlInterface $urlBuilder */
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
            ['_secure' => true]
        );
        return "(function(){window.location='{$targetUrl}';})();";
    }
}
