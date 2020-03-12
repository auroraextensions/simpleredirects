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
    Api\RuleRepositoryInterface,
    Component\Utils\ArrayTrait,
    Model\ResourceModel\Rule\Collection,
    Model\ResourceModel\Rule\CollectionFactory
};
use Magento\Framework\{
    App\RequestInterface,
    Api\SearchCriteriaBuilder,
    Api\SortOrderBuilder,
    Option\ArrayInterface
};

class Rule implements ArrayInterface
{
    /**
     * @method array flattenArray()
     */
    use ArrayTrait;

    /** @constant string PRIMARY_KEY */
    private const PRIMARY_KEY = 'rule_id';

    /** @property CollectionFactory $collectionFactory */
    private $collectionFactory;

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
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param RuleRepositoryInterface $ruleRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        RuleRepositoryInterface $ruleRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
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
            ->addFilter('rule_id', $this->getMergedRules(), 'nin')
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
     * @return array
     */
    private function getLinkedRules(): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory
            ->create()
            ->addFieldToSelect('parent_id')
            ->addFieldToFilter('parent_id', ['notnull' => true]);

        /** @var array $ids */
        $ids = $this->flattenArray($collection->toArray()['items'] ?? []);

        return array_map('intval', $ids);
    }

    /**
     * @return array
     */
    private function getMergedRules(): array
    {
        /** @var int $ruleId */
        $ruleId = $this->getRuleId();

        /** @var array $excludes */
        $excludes = $ruleId !== 0 ? [$ruleId] : [];

        /** @var array $merged */
        $merged = array_replace(
            $this->getLinkedRules(),
            $excludes
        );

        return array_values($merged);
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
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->options;
    }
}
