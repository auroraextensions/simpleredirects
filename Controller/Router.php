<?php
/**
 * Router.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Controller
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Controller;

use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Component\Config\ModuleConfigTrait;
use AuroraExtensions\SimpleRedirects\Csi\Config\ModuleConfigInterface;
use AuroraExtensions\SimpleRedirects\Csi\Validator\RuleValidatorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Action\Redirect;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Url\QueryParamsResolverInterface;

use function array_key_exists;
use function trim;

class Router implements RouterInterface
{
    /**
     * @var ModuleConfigInterface $moduleConfig
     * @method bool isModuleEnabled()
     */
    use ModuleConfigTrait;

    /** @var ActionFactory $actionFactory */
    private $actionFactory;

    /** @var QueryParamsResolverInterface $queryParamsResolver */
    private $queryParamsResolver;

    /** @var ResponseInterface $response */
    private $response;

    /** @var RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @var RuleValidatorInterface $ruleValidator */
    private $ruleValidator;

    /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var SortOrderBuilder $sortOrderBuilder */
    private $sortOrderBuilder;

    /** @var UrlInterface $urlBuilder */
    private $urlBuilder;

    /**
     * @param ActionFactory $actionFactory
     * @param ModuleConfigInterface $moduleConfig
     * @param QueryParamsResolverInterface $queryParamsResolver
     * @param ResponseInterface $response
     * @param RuleRepositoryInterface $ruleRepository
     * @param RuleValidatorInterface $ruleValidator
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param UrlInterface $urlBuilder
     * @return void
     */
    public function __construct(
        ActionFactory $actionFactory,
        ModuleConfigInterface $moduleConfig,
        QueryParamsResolverInterface $queryParamsResolver,
        ResponseInterface $response,
        RuleRepositoryInterface $ruleRepository,
        RuleValidatorInterface $ruleValidator,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        UrlInterface $urlBuilder
    ) {
        $this->actionFactory = $actionFactory;
        $this->moduleConfig = $moduleConfig;
        $this->queryParamsResolver = $queryParamsResolver;
        $this->response = $response;
        $this->ruleRepository = $ruleRepository;
        $this->ruleValidator = $ruleValidator;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function match(RequestInterface $request)
    {
        if (!$this->isModuleEnabled()) {
            return null;
        }

        /** @var RuleInterface $rule */
        foreach ($this->getRules() as $rule) {
            /** @var string $token */
            $token = $rule->getToken();

            if ($this->hasQueryParam($request, $token)) {
                return null;
            }

            /** @var string|null $target */
            $target = $rule->getTarget();

            if ($this->validate($rule) && $target !== null) {
                $this->addQueryParams([$token => null]);
                $this->response->setRedirect(
                    $this->getRedirectUrl($target),
                    $rule->getRedirectType()
                );
                return $this->actionFactory->create(Redirect::class);
            }
        }

        return null;
    }

    /**
     * @return RuleInterface[]
     */
    private function getRules(): array
    {
        /** @var SortOrder $sortOrder */
        $sortOrder = $this->sortOrderBuilder
            ->setField('priority')
            ->setAscendingDirection()
            ->create();

        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->searchCriteriaBuilder
            ->addFilter('is_active', '1')
            ->setSortOrders([$sortOrder])
            ->create();

        return $this->ruleRepository
            ->getList($criteria)
            ->getItems();
    }

    /**
     * @param string $url
     * @return string
     */
    private function getRedirectUrl(string $url): string
    {
        /** @var array $params */
        $params = [
            '_direct' => trim($url, '/'),
        ];
        return $this->urlBuilder->getUrl('', $params);
    }

    /**
     * @param array $params
     * @return void
     */
    private function addQueryParams(array $params = []): void
    {
        /** @var array $result */
        $result = [];

        /** @var string $key */
        /** @var int|string|null $value */
        foreach ($params as $key => $value) {
            $result[$key] = $value ?? '';
        }

        $this->queryParamsResolver->addQueryParams($result);
    }

    /**
     * @param RequestInterface $request
     * @param string $paramKey
     * @return bool
     */
    private function hasQueryParam(
        RequestInterface $request,
        string $paramKey
    ): bool {
        /** @var array $paramKeys */
        $paramKeys = $request->getQuery()->toArray();
        return array_key_exists($paramKey, $paramKeys);
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function validate(RuleInterface $rule): bool
    {
        return $this->ruleValidator->validate($rule);
    }
}
