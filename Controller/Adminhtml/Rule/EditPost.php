<?php
/**
 * EditPost.php
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

namespace AuroraExtensions\SimpleRedirects\Controller\Adminhtml\Rule;

use AuroraExtensions\ModuleComponents\Exception\ExceptionFactory;
use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Api\Data\RuleInterfaceFactory,
    Api\RuleRepositoryInterface,
    Component\Config\ModuleConfigTrait,
    Csi\Config\ModuleConfigInterface,
    Model\Request\Token
};
use Magento\Framework\{
    App\Action\Action,
    App\Action\Context,
    App\Action\HttpPostActionInterface,
    Controller\Result\JsonFactory as ResultJsonFactory,
    Data\Form\FormKey\Validator as FormKeyValidator,
    Escaper,
    Event\ManagerInterface as EventManagerInterface,
    Exception\AlreadyExistsException,
    Exception\LocalizedException,
    Exception\NoSuchEntityException,
    Serialize\Serializer\Json,
    UrlInterface
};

class EditPost extends Action implements HttpPostActionInterface
{
    /**
     * @property ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @property Escaper $escaper */
    protected $escaper;

    /** @property EventManagerInterface $eventManager */
    protected $eventManager;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property FormKeyValidator $formKeyValidator */
    protected $formKeyValidator;

    /** @property ResultJsonFactory $resultJsonFactory */
    protected $resultJsonFactory;

    /** @property Json $serializer */
    protected $serializer;

    /** @property RuleInterfaceFactory $ruleFactory */
    protected $ruleFactory;

    /** @property RuleRepositoryInterface $ruleRepository */
    protected $ruleRepository;

    /** @property UrlInterface $urlBuilder */
    protected $urlBuilder;

    /**
     * @param Context $context
     * @param Escaper $escaper
     * @param EventManagerInterface $eventManager
     * @param ExceptionFactory $exceptionFactory
     * @param FormKeyValidator $formKeyValidator
     * @param ModuleConfigInterface $moduleConfig
     * @param ResultJsonFactory $resultJsonFactory
     * @param Json $serializer
     * @param RuleInterfaceFactory $ruleFactory
     * @param RuleRepositoryInterface $ruleRepository
     * @param UrlInterface $urlBuilder
     * @return void
     */
    public function __construct(
        Context $context,
        Escaper $escaper,
        EventManagerInterface $eventManager,
        ExceptionFactory $exceptionFactory,
        FormKeyValidator $formKeyValidator,
        ModuleConfigInterface $moduleConfig,
        ResultJsonFactory $resultJsonFactory,
        Json $serializer,
        RuleInterfaceFactory $ruleFactory,
        RuleRepositoryInterface $ruleRepository,
        UrlInterface $urlBuilder
    ) {
        parent::__construct($context);
        $this->escaper = $escaper;
        $this->eventManager = $eventManager;
        $this->exceptionFactory = $exceptionFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->moduleConfig = $moduleConfig;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->serializer = $serializer;
        $this->ruleFactory = $ruleFactory;
        $this->ruleRepository = $ruleRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        /** @var Magento\Framework\App\RequestInterface $request */
        $request = $this->getRequest();

        /** @var array $response */
        $response = [];

        /** @var Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        if (!$request->isPost()) {
            return $resultJson->setData([
                'errors' => true,
                'message' => __('Invalid request type. Must be POST request.'),
            ]);
        }

        if (!$this->formKeyValidator->validate($request)) {
            return $resultJson->setData([
                'errors' => true,
                'message' => __('Invalid form key.'),
            ]);
        }

        /** @var string|null $ruleName */
        $ruleName = $request->getPostValue('name');
        $ruleName = $ruleName !== null && !empty($ruleName)
            ? $this->escaper->escapeHtml($ruleName)
            : null;

        /** @var string|null $ruleType */
        $ruleType = $request->getPostValue('rule_type');
        $ruleType = $ruleType !== null && !empty($ruleType)
            ? $this->escaper->escapeHtml($ruleType)
            : null;

        /** @var int|string|null $parentId */
        $parentId = $request->getPostValue('parent_id');
        $parentId = is_numeric($parentId) ? (int) $parentId : null;

        /** @var string|null $matchType */
        $matchType = $request->getPostValue('match_type');
        $matchType = $matchType !== null && !empty($matchType)
            ? $this->escaper->escapeHtml($matchType)
            : null;

        /** @var string|null $pattern */
        $pattern = $request->getPostValue('pattern');
        $pattern = $pattern !== null && !empty($pattern)
            ? $this->escaper->escapeHtml($pattern)
            : null;

        /** @var string|null $target */
        $target = $request->getPostValue('target');
        $target = $target !== null && !empty($target)
            ? $this->escaper->escapeHtml($target)
            : null;

        /** @var int|string|null $priority */
        $priority = $request->getPostValue('priority');
        $priority = is_numeric($priority) ? (int) $priority : 10;

        /** @var string|null $redirectType */
        $redirectType = $request->getPostValue('redirect_type');
        $redirectType = $redirectType !== null && !empty($redirectType)
            ? $this->escaper->escapeHtml($redirectType)
            : null;

        /** @var bool $isActive */
        $isActive = $request->getPostValue('is_active') ?? false;
        $isActive = filter_var(
            $isActive,
            FILTER_VALIDATE_BOOLEAN
        );

        try {
            /** @var int|string|null $ruleId */
            $ruleId = $request->getParam('rule_id');
            $ruleId = $ruleId !== null ? (int) $ruleId : null;

            if ($ruleId !== null) {
                /** @var RuleInterface $rule */
                $rule = $this->ruleRepository->getById($ruleId);
                $rule->addData([
                    'name' => $ruleName,
                    'rule_type' => $ruleType,
                    'parent_id' => $parentId,
                    'match_type' => $matchType,
                    'redirect_type' => $redirectType,
                    'pattern' => $pattern,
                    'target' => $target,
                    'priority' => $priority,
                    'token' => Token::generate(),
                    'is_active' => $isActive,
                ]);
                $this->ruleRepository->save($rule);

                /** @var string $viewUrl */
                $viewUrl = $this->urlBuilder
                    ->getUrl('simpleredirects/rule/view', [
                        'rule_id'  => $ruleId,
                        '_secure' => true,
                    ]);

                return $resultJson->setData([
                    'success' => true,
                    'isAjax'  => true,
                    'message' => __('Successfully edited rule.'),
                    'viewUrl' => $viewUrl,
                ]);
            }

            /** @var LocalizedException $exception */
            $exception = $this->exceptionFactory->create(
                LocalizedException::class
            );

            throw $exception;
        } catch (NoSuchEntityException $e) {
            $response = [
                'error' => true,
                'messages' => [$e->getMessage()],
            ];
        } catch (LocalizedException $e) {
            $response = [
                'error' => true,
                'messages' => [$e->getMessage()],
            ];
        }

        return $resultJson->setData($response);
    }
}
