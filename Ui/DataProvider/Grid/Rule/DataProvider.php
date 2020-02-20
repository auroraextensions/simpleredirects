<?php
/**
 * DataProvider.php
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

namespace AuroraExtensions\SimpleRedirects\Ui\DataProvider\Grid\Rule;

use Countable;
use AuroraExtensions\SimpleRedirects\{
    Model\ResourceModel\Rule\Collection,
    Model\ResourceModel\Rule\CollectionFactory
};
use Magento\Framework\{
    Api\Filter,
    View\Element\UiComponent\DataProvider\DataProviderInterface
};
use Magento\Ui\{
    DataProvider\AbstractDataProvider,
    DataProvider\AddFieldToCollectionInterface,
    DataProvider\AddFilterToCollectionInterface
};

class DataProvider extends AbstractDataProvider implements
    Countable,
    DataProviderInterface
{
    /** @property AddFieldToCollectionInterface[] $addFieldStrategies */
    private $addFieldStrategies;

    /** @property AddFilterToCollectionInterface[] $addFilterStrategies */
    private $addFilterStrategies;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param AddFieldToCollectionInterface[] $addFieldStrategies
     * @param AddFilterToCollectionInterface[] $addFilterStrategies
     * @param CollectionFactory $collectionFactory
     * @return void
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        CollectionFactory $collectionFactory
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->collection = $collectionFactory->create();
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
    public function addField($field, $alias = null)
    {
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
