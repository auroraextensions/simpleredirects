<?php
/**
 * DeleteButton.php
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

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

use function __;
use function array_replace_recursive;

class DeleteButton implements ButtonProviderInterface
{
    private const ACL_RESOURCE = 'AuroraExtensions_SimpleRedirects::delete';

    /** @var string $aclResource */
    private $aclResource;

    /** @var string $buttonId */
    private $buttonId;

    /** @var array $components */
    private $components = [];

    /** @var FormKey $formKey */
    private $formKey;

    /** @var RequestInterface $request */
    private $request;

    /** @var string $route */
    private $route;

    /** @var UrlInterface $urlBuilder */
    private $urlBuilder;

    /**
     * @param FormKey $formKey
     * @param RequestInterface $request
     * @param UrlInterface $urlBuilder
     * @param string $aclResource
     * @param string $buttonId
     * @param string $route
     * @param array $components
     * @return void
     */
    public function __construct(
        FormKey $formKey,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        string $aclResource = self::ACL_RESOURCE,
        string $buttonId = 'delete',
        string $route = '*',
        array $components = []
    ) {
        $this->formKey = $formKey;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->aclResource = $aclResource;
        $this->buttonId = $buttonId;
        $this->route = $route;
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
            'aclResource' => $this->aclResource,
            'class' => 'delete',
            'data_attribute' => [
                'mage-init' => $this->components,
                'url' => $this->getDeleteUrl(),
            ],
            'id' => $this->buttonId,
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
                'form_key' => $this->getFormKey(),
                '_secure' => true,
            ]
        );
    }

    /**
     * @return string
     */
    private function getFormKey(): string
    {
        return $this->formKey
            ->getFormKey();
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
