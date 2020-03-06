<?php
/**
 * Rule.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Config\Source\Select;

use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Api\RuleRepositoryInterface
};
use Magento\Framework\{
    App\RequestInterface,
    Api\SearchCriteriaBuilder,
    Api\SortOrderBuilder,
    Option\ArrayInterface
};

class Rule implements ArrayInterface
{
    /** @property array $options */
    private $options = [];

    /** @property RequestInterface $request */
    private $request;

    /** @property RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @property SearchCriteriaBuilder $searchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @property SortOrderBuilder $sortOrderBuilder */
    private $sortOrderBuilder;

    /**
     * @param RequestInterface $request
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @return void
     */
    public function __construct(
        RequestInterface $request,
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->request = $request;
        $this->ruleRepository = $ruleRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->initialize();
    }

    /**
     * @return void
     */
    private function initialize(): void
    {
        /** @var SortOrder $sortOrder */
        $sortOrder = $this->sortOrderBuilder
            ->setField('priority')
            ->setAscendingDirection()
            ->create();

        /** @var SearchCriteriaInterface $criteria */
        $criteria = $this->searchCriteriaBuilder
            ->addFilter('is_active', '1')
            ->addFilter('rule_id', $this->getRuleId(), 'neq')
            ->setSortOrders([$sortOrder])
            ->create();

        /** @var RuleInterface[] $rules */
        $rules = $this->ruleRepository
            ->getList($criteria)
            ->getItems();

        /** @var RuleInterface $rule */
        foreach ($rules as $rule) {
            $this->setOption(
                $rule->getName(),
                $rule->getId()
            );
        }
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
     * @param string $key
     * @param int|string|null $value
     * @return void
     */
    private function setOption(string $key, $value = null): void
    {
        $this->options[] = [
            'label' => __($key),
            'value' => $value ?? $key,
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->options;
    }
}
