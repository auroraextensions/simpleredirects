<?php
/**
 * View.php
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

namespace AuroraExtensions\SimpleRedirects\Model\ViewModel\Rule;

use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Api\RuleRepositoryInterface,
    Csi\Config\ModuleConfigInterface,
    Exception\ExceptionFactory
};
use Magento\Framework\{
    App\RequestInterface,
    Exception\LocalizedException,
    Exception\NoSuchEntityException,
    Message\ManagerInterface as MessageManagerInterface,
    View\Element\Block\ArgumentInterface
};
use Magento\Store\Model\StoreManagerInterface;

class View implements ArgumentInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @property ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @property MessageManagerInterface $messageManager */
    private $messageManager;

    /** @property RequestInterface $request */
    private $request;

    /** @property RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @property StoreManagerInterface $storeManager */
    private $storeManager;

    /**
     * @param ExceptionFactory $exceptionFactory
     * @param MessageManagerInterface $messageManager
     * @param ModuleConfigInterface $moduleConfig
     * @param RequestInterface $request
     * @param RuleRepositoryInterface $ruleRepository
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        ExceptionFactory $exceptionFactory,
        MessageManagerInterface $messageManager,
        ModuleConfigInterface $moduleConfig,
        RequestInterface $request,
        RuleRepositoryInterface $ruleRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->messageManager = $messageManager;
        $this->moduleConfig = $moduleConfig;
        $this->request = $request;
        $this->ruleRepository = $ruleRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string $type
     * @param string $key
     * @param string
     */
    public function getFrontLabel(string $type, string $key): string
    {
        /** @var array $labels */
        $labels = $this->moduleConfig
            ->getContainer()
            ->getData($type) ?? [];

        return $labels[$key] ?? $key;
    }
}
