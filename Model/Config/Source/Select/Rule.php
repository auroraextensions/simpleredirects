<?php
/**
 * Rule.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\Config\Source\Select
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Config\Source\Select;

use AuroraExtensions\ModuleComponents\Component\Utils\ArrayTrait;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\Collection;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;

use function __;
use function array_map;
use function array_replace;
use function array_values;

class Rule implements OptionSourceInterface
{
    /**
     * @method array flatten()
     */
    use ArrayTrait;

    private const PRIMARY_KEY = 'rule_id';

    /** @var CollectionFactory $collectionFactory */
    private $collectionFactory;

    /** @var array $options */
    private $options = [];

    /** @var RequestInterface $request */
    private $request;

    /** @var RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var SortOrderBuilder $sortOrderBuilder */
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

        return array_map(
            'intval',
            $this->flatten($collection->toArray()['items'] ?? [])
        );
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
    private function setOption(
        string $key,
        $value = null
    ): void {
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
