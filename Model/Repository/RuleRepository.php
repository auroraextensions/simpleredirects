<?php
/**
 * RuleRepository.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\Repository
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Repository;

use AuroraExtensions\ModuleComponents\Component\Repository\AbstractRepositoryTrait;
use AuroraExtensions\ModuleComponents\Exception\ExceptionFactory;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterfaceFactory;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleSearchResultsInterfaceFactory;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule as RuleResource;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\Collection;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;

use function __;

class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var CollectionFactory $collectionFactory
     * @var RuleSearchResultsInterfaceFactory $searchResultsFactory
     * @method void addFilterGroupToCollection()
     * @method string getDirection()
     * @method SearchResultsInterface getList()
     */
    use AbstractRepositoryTrait;

    /** @var ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @var RuleInterfaceFactory $ruleFactory */
    private $ruleFactory;

    /** @var RuleResourceInterface $ruleResource */
    private $ruleResource;

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
