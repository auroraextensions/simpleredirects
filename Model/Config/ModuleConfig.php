<?php
/**
 * ModuleConfig.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Config;

use AuroraExtensions\SimpleRedirects\{
    Component\Data\Container\DataContainerTrait,
    Csi\Config\ModuleConfigInterface,
    Csi\Data\Container\DataContainerInterface
};
use Magento\Framework\{
    App\Config\ScopeConfigInterface,
    DataObject,
    DataObject\Factory as DataObjectFactory
};
use Magento\Store\{
    Model\ScopeInterface as StoreScopeInterface,
    Model\Store
};

class ModuleConfig implements ModuleConfigInterface, DataContainerInterface
{
    /**
     * @property DataObject $container
     * @method DataObject|null getContainer()
     * @method DataContainerInterface setContainer()
     */
    use DataContainerTrait;

    /** @constant string XML_PATH_MODULE_GENERAL_MODULE_ENABLE */
    public const XML_PATH_MODULE_GENERAL_MODULE_ENABLE = 'simpleredirects/general/enable';

    /** @property ScopeConfigInterface $scopeConfig */
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
        $this->container = $dataObjectFactory->create($data);
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
    ): ?string
    {
        return $this->scopeConfig
            ->getValue($path, $scope, $store);
    }

    /**
     * @param int $store
     * @param string $scope
     * @return bool
     */
    public function isModuleEnabled(
        int $store = Store::DEFAULT_STORE_ID,
        string $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_MODULE_GENERAL_MODULE_ENABLE,
            $scope,
            $store
        );
    }
}
