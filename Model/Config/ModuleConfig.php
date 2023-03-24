<?php
/**
 * ModuleConfig.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\Config
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Config;

use AuroraExtensions\ModuleComponents\Component\Data\Container\DataContainerTrait;
use AuroraExtensions\SimpleRedirects\Csi\Config\ModuleConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\Factory as DataObjectFactory;
use Magento\Store\Model\Store;

class ModuleConfig implements ModuleConfigInterface
{
    /**
     * @var DataObject $container
     * @method DataObject|null getContainer()
     * @method $this setContainer()
     */
    use DataContainerTrait;

    public const XML_PATH_MODULE_GENERAL_MODULE_ENABLE = 'simpleredirects/general/enable';

    /** @var ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param DataObjectFactory $dataObjectFactory
     * @param array $data
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->setContainer($dataObjectFactory->create($data));
    }

    /**
     * @param string $path
     * @param int $store
     * @param string $scope
     * @return string|null
     */
    public function getConfigValue(
        string $path,
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): ?string {
        return $this->scopeConfig->getValue(
            $path,
            $scope,
            $store
        );
    }

    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function isModuleEnabled(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): bool {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_MODULE_GENERAL_MODULE_ENABLE,
            $scope,
            $store
        );
    }
}
