<?php
/**
 * DeletePost.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Controller\Adminhtml\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Controller\Adminhtml\Rule;

use AuroraExtensions\ModuleComponents\Exception\ExceptionFactory;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Component\Config\ModuleConfigTrait;
use AuroraExtensions\SimpleRedirects\Csi\Config\ModuleConfigInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;

use function __;

class DeletePost extends Action implements HttpPostActionInterface
{
    /**
     * @var ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @var EventManagerInterface $eventManager */
    protected $eventManager;

    /** @var ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @var FormKeyValidator $formKeyValidator */
    protected $formKeyValidator;

    /** @var RuleRepositoryInterface $ruleRepository */
    protected $ruleRepository;

    /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /** @var UrlInterface $urlBuilder */
    protected $urlBuilder;

    /**
     * @param Context $context
     * @param EventManagerInterface $eventManager
     * @param ExceptionFactory $exceptionFactory
     * @param FormKeyValidator $formKeyValidator
     * @param ModuleConfigInterface $moduleConfig
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param UrlInterface $urlBuilder
     * @return void
     */
    public function __construct(
        Context $context,
        EventManagerInterface $eventManager,
        ExceptionFactory $exceptionFactory,
        FormKeyValidator $formKeyValidator,
        ModuleConfigInterface $moduleConfig,
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);
        $this->eventManager = $eventManager;
        $this->exceptionFactory = $exceptionFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->moduleConfig = $moduleConfig;
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        /** @var Magento\Framework\App\RequestInterface $request */
        $request = $this->getRequest();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('simpleredirects/rule/index');

        if (!$request->isPost()) {
            $this->messageManager->addErrorMessage(
                __('Invalid request type. Must be POST request.')
            );
            return $resultRedirect;
        }

        if (!$this->formKeyValidator->validate($request)) {
            $this->messageManager->addErrorMessage(
                __('Invalid form key.')
            );
            return $resultRedirect;
        }

        try {
            /** @var int|string|null $ruleId */
            $ruleId = $request->getParam('rule_id');
            $ruleId = $ruleId !== null ? (int) $ruleId : null;

            if ($ruleId !== null) {
                $this->ruleRepository->deleteById($ruleId);
                $this->unlinkDependents($ruleId);
                $this->messageManager->addSuccessMessage(
                    __('Successfully deleted rule.')
                );
                return $resultRedirect;
            }

            /** @var LocalizedException $exception */
            $exception = $this->exceptionFactory->create(
                LocalizedException::class
            );
            throw $exception;
        } catch (NoSuchEntityException | LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect;
    }

    /**
     * @param int $parentId
     * @return void
     */
    private function unlinkDependents(int $parentId): void
    {
        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->searchCriteriaBuilder
            ->addFilter('parent_id', $parentId)
            ->create();

        /** @var RuleInterface[] $rules */
        $rules = $this->ruleRepository
            ->getList($criteria)
            ->getItems();

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            $rule->setParentId(null);
            $this->ruleRepository->save($rule);
        }
    }
}
