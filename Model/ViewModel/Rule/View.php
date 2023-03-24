<?php
/**
 * View.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\ViewModel\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\ViewModel\Rule;

use AuroraExtensions\ModuleComponents\Exception\ExceptionFactory;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Component\Config\ModuleConfigTrait;
use AuroraExtensions\SimpleRedirects\Csi\Config\ModuleConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class View implements ArgumentInterface
{
    /**
     * @var ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @var ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @var MessageManagerInterface $messageManager */
    private $messageManager;

    /** @var RequestInterface $request */
    private $request;

    /** @var RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @var StoreManagerInterface $storeManager */
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
    public function getLabel(string $type, string $key): string
    {
        /** @var array $labels */
        $labels = $this->moduleConfig->getContainer()->getData($type) ?? [];
        return $labels[$key] ?? $key;
    }

    /**
     * @return RuleInterface|null
     */
    public function getRule(): ?RuleInterface
    {
        /** @var int|string|null $ruleId */
        $ruleId = $this->request->getParam('rule_id');

        if ($ruleId !== null) {
            try {
                return $this->ruleRepository->getById((int) $ruleId);
            } catch (NoSuchEntityException | LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getParentName(): string
    {
        /** @var int|string|null $ruleId */
        $ruleId = $this->request->getParam('rule_id');

        if ($ruleId !== null) {
            try {
                /** @var RuleInterface $rule */
                $rule = $this->ruleRepository->getById((int) $ruleId);

                /** @var int|null $parentId */
                $parentId = $rule->getParentId();

                if ($parentId !== null) {
                    /** @var RuleInterface $parent */
                    $parent = $this->ruleRepository->getById($parentId);
                    return $parent->getName();
                }
            } catch (NoSuchEntityException | LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return '---';
    }
}
