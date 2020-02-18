<?php
/**
 * Router.php
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

namespace AuroraExtensions\SimpleRedirects\Controller;

use AuroraExtensions\SimpleRedirects\{
    Component\Config\ModuleConfigTrait,
    Csi\Config\ModuleConfigInterface
};
use Magento\Framework\{
    App\ActionFactory,
    App\RequestInterface,
    App\ResponseInterface,
    App\RouterInterface
};

class Router implements RouterInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @property ActionFactory $actionFactory */
    private $actionFactory;

    /** @property ResponseInterface $response */
    private $response;

    /**
     * @param ActionFactory $actionFactory
     * @param ModuleConfigInterface $moduleConfig
     * @param ResponseInterface $response
     * @return void
     */
    public function __construct(
        ActionFactory $actionFactory,
        ModuleConfigInterface $moduleConfig,
        ResponseInterface $response
    ) {
        $this->actionFactory = $actionFactory;
        $this->moduleConfig = $moduleConfig;
        $this->response = $response;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        if (!$this->isModuleEnabled()) {
            return null;
        }

        /** @var string $actionType */
        $actionType = $this->getActionType();

        return $this->actionFactory->create($actionType);
    }

    /**
     * @return string
     */
    private function getActionType(): string
    {
        return '';
    }
}
