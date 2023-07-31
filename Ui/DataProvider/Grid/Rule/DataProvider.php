<?php
/**
 * DataProvider.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Ui\DataProvider\Grid\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Ui\DataProvider\Grid\Rule;

use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\Collection;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule\CollectionFactory;
use Countable;
use Magento\Framework\Api\Filter;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class DataProvider extends AbstractDataProvider implements
    DataProviderInterface,
    Countable
{
    /** @var AddFieldToCollectionInterface[] $addFieldStrategies */
    private $addFieldStrategies;

    /** @var AddFilterToCollectionInterface[] $addFilterStrategies */
    private $addFilterStrategies;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param AddFieldToCollectionInterface[] $addFieldStrategies
     * @param AddFilterToCollectionInterface[] $addFilterStrategies
     * @param array $meta
     * @param array $data
     * @return void
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collectionFactory->create();
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->getCollection()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function addField(
        $field,
        $alias = null
    ) {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]
                 ->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(Filter $filter)
    {
        /** @var string $field */
        $field = $filter->getField();

        if (isset($this->addFilterStrategies[$field])) {
            $this->addFilterStrategies[$field]
                 ->addFilter(
                    $this->getCollection(),
                    $field,
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }
}
