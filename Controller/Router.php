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
    Api\Data\RuleInterface,
    Api\RuleRepositoryInterface,
    Component\Config\ModuleConfigTrait,
    Csi\Config\ModuleConfigInterface,
    Csi\Validator\RuleValidatorInterface
};
use Magento\Framework\{
    Api\SearchCriteriaBuilder,
    App\ActionFactory,
    App\Action\Redirect,
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

    /** @property RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @property RuleValidatorInterface $ruleValidator */
    private $ruleValidator;

    /** @property SearchCriteriaBuilder $searchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * @param ActionFactory $actionFactory
     * @param ModuleConfigInterface $moduleConfig
     * @param ResponseInterface $response
     * @param RuleRepositoryInterface $ruleRepository
     * @param RuleValidatorInterface $ruleValidator
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
     */
    public function __construct(
        ActionFactory $actionFactory,
        ModuleConfigInterface $moduleConfig,
        ResponseInterface $response,
        RuleRepositoryInterface $ruleRepository,
        RuleValidatorInterface $ruleValidator,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->actionFactory = $actionFactory;
        $this->moduleConfig = $moduleConfig;
        $this->response = $response;
        $this->ruleRepository = $ruleRepository;
        $this->ruleValidator = $ruleValidator;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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

        /** @var RuleInterface[] $rules */
        $rules = $this->getRules();

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            if ($this->validate($rule)) {
                $this->response->setRedirect(
                    $rule->getTarget(),
                    $rule->getRedirectType()
                );

                return $this->actionFactory
                    ->create(Redirect::class);
            }
        }

        return null;
    }

    /**
     * @return RuleInterface[]
     */
    private function getRules(): array
    {
        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->searchCriteriaBuilder->create();

        return $this->ruleRepository
            ->getList($criteria)
            ->getItems();
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function validate(RuleInterface $rule): bool
    {
        return $this->ruleValidator
            ->validate($rule);
    }
}
