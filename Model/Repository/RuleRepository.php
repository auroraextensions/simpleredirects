<?php
/**
 * RuleRepository.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Repository;

use Magento\Framework\{
    Api\SearchCriteriaInterface,
    Api\SortOrder,
    Exception\NoSuchEntityException
};
use AuroraExtensions\SimpleRedirects\{
    Api\RuleRepositoryInterface,
    Api\Data\RuleInterface,
    Api\Data\RuleInterfaceFactory,
    Api\Data\RuleSearchResultsInterfaceFactory,
    Component\Repository\AbstractRepositoryTrait,
    Exception\ExceptionFactory,
    Model\ResourceModel\Rule as RuleResource,
    Model\ResourceModel\Rule\Collection,
    Model\ResourceModel\Rule\CollectionFactory
};

class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @method void addFilterGroupToCollection()
     * @method string getDirection()
     * @method SearchResultsInterface getList()
     */
    use AbstractRepositoryTrait;

    /** @property CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /** @property ExceptionFactory $exceptionFactory */
    protected $exceptionFactory;

    /** @property RuleInterfaceFactory $ruleFactory */
    protected $ruleFactory;

    /** @property RuleResourceInterface $ruleResource */
    protected $ruleResource;

    /** @property RuleSearchResultsInterfaceFactory $searchResultsFactory */
    protected $searchResultsFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ExceptionFactory $exceptionFactory
     * @param RuleInterfaceFactory $ruleFactory
     * @param RuleResource $ruleResource
     * @param RuleSearchResultsInterfaceFactory $searchResultsFactory
     * @return void
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ExceptionFactory $exceptionFactory,
        RuleInterfaceFactory $ruleFactory,
        RuleResource $ruleResource,
        RuleSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->exceptionFactory = $exceptionFactory;
        $this->ruleFactory = $ruleFactory;
        $this->ruleResource = $ruleResource;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param int $id
     * @return RuleInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): RuleInterface
    {
        /** @var RuleInterface $rule */
        $rule = $this->ruleFactory->create();
        $this->ruleResource->load($rule, $id);

        if (!$rule->getId()) {
            /** @var NoSuchEntityException $exception */
            $exception = $this->exceptionFactory->create(
                NoSuchEntityException::class,
                __('Unable to locate matching rule information.')
            );

            throw $exception;
        }

        return $rule;
    }

    /**
     * @param RuleInterface $rule
     * @return int
     */
    public function save(RuleInterface $rule): int
    {
        $this->ruleResource->save($rule);
        return (int) $rule->getId();
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function delete(RuleInterface $rule): bool
    {
        return $this->deleteById((int) $rule->getId());
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        /** @var RuleInterface $rule */
        $rule = $this->ruleFactory->create();
        $rule->setId($id);

        return (bool) $this->ruleResource->delete($rule);
    }
}
