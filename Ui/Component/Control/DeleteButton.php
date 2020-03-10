<?php
/**
 * DeleteButton.php
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
    App\RequestInterface,
    UrlInterface,
    View\Element\UiComponent\Control\ButtonProviderInterface
};

class DeleteButton implements ButtonProviderInterface
{
    /** @constant string ACL_RESOURCE */
    private const ACL_RESOURCE = 'AuroraExtensions_SimpleRedirects::delete';

    /** @property string $aclResource */
    private $aclResource;

    /** @property RequestInterface $request */
    private $request;

    /** @property string $route */
    private $route;

    /** @property UrlInterface $urlBuilder */
    private $urlBuilder;

    /**
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param string $aclResource
     * @param string $route
     * @return void
     */
    public function __construct(
        RequestInterface $request,
        UrlInterface $urlBuilder,
        string $aclResource = self::ACL_RESOURCE,
        string $route = null
    ) {
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->aclResource = $aclResource;
        $this->route = $route ?? '';
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'aclResource' => $this->aclResource,
            'class' => 'delete',
            'data_attribute' => [
                'url' => $this->getDeleteUrl(),
            ],
            'label' => __('Delete'),
            'on_click' => '',
            'sort_order' => 20,
        ];
    }

    /**
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->urlBuilder->getUrl(
            $this->route,
            [
                'rule_id' => $this->getRuleId(),
                '_secure' => true,
            ]
        );
    }

    /**
     * @return int
     */
    private function getRuleId(): int
    {
        return (int) $this->request
            ->getParam('rule_id');
    }
}
