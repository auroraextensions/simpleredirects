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
    Data\Form\FormKey,
    UrlInterface,
    View\Element\UiComponent\Control\ButtonProviderInterface
};

class DeleteButton implements ButtonProviderInterface
{
    /** @constant string ACL_RESOURCE */
    private const ACL_RESOURCE = 'AuroraExtensions_SimpleRedirects::delete';

    /** @property string $aclResource */
    private $aclResource;

    /** @property string $buttonId */
    private $buttonId;

    /** @property array $components */
    private $components = [];

    /** @property FormKey $formKey */
    private $formKey;

    /** @property RequestInterface $request */
    private $request;

    /** @property string $route */
    private $route;

    /** @property UrlInterface $urlBuilder */
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
