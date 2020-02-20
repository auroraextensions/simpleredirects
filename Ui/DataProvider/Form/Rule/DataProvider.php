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

namespace AuroraExtensions\SimpleRedirects\Ui\DataProvider\Form\Rule;

use Countable;
use AuroraExtensions\SimpleRedirects\{
    Component\Ui\DataProvider\Modifier\ModifierPoolTrait,
    Model\ResourceModel\Rule\Collection,
    Model\ResourceModel\Rule\CollectionFactory
};
use Magento\Framework\{
    Api\FilterBuilder,
    Api\Search\SearchCriteria,
    Api\Search\SearchCriteriaBuilder,
    Api\Search\SearchResultInterface,
    App\RequestInterface,
    View\Element\UiComponent\DataProvider\DataProviderInterface
};
use Magento\Ui\{
    DataProvider\AbstractDataProvider,
    DataProvider\Modifier\ModifierInterface,
    DataProvider\Modifier\PoolInterface
};

class DataProvider extends AbstractDataProvider implements
    Countable,
    DataProviderInterface
{
    /**
     * @property PoolInterface $modifierPool
     * @method PoolInterface getModifierPool()
     * @method ModifierInterface[] getModifiers()
     */
    use ModifierPoolTrait;

    /** @constant string WILDCARD */
    public const WILDCARD = '*';

    /** @property FilterBuilder $filterBuilder */
    protected $filterBuilder;

    /** @property array $loadedData */
    protected $loadedData = [];

    /** @property RequestInterface $request */
    protected $request;

    /** @property SearchCriteriaBuilder $searchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param CollectionFactory $collectionFactory
     * @param FilterBuilder $filterBuilder
     * @param PoolInterface $modifierPool
     * @param RequestInterface $request
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return void
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        CollectionFactory $collectionFactory,
        FilterBuilder $filterBuilder,
        PoolInterface $modifierPool,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
        $this->collection = $collectionFactory->create();
        $this->filterBuilder = $filterBuilder;
        $this->modifierPool = $modifierPool;
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->prepareSubmitUrl();
    }

    /**
     * @return void
     */
    public function prepareSubmitUrl(): void
    {
        if (isset($this->data['config']['submit_url'])) {
            $this->parseSubmitUrl();
        }

        if (isset($this->data['config']['filter_url_params'])) {
            /** @var string $field */
            /** @var mixed $value */
            foreach ($this->data['config']['filter_url_params'] as $field => $value) {
                $value = $value !== static::WILDCARD
                    ? (string) $value
                    : $this->request->getParam($field);

                if ($value) {
                    $this->data['config']['submit_url'] = sprintf(
                        '%s%s/%s/',
                        $this->data['config']['submit_url'],
                        $field,
                        $value
                    );

                    /** @var Filter $filter */
                    $filter = $this->filterBuilder
                        ->setField($field)
                        ->setValue($value)
                        ->setConditionType('eq')
                        ->create();

                    $this->searchCriteriaBuilder->addFilter($filter);
                }
            }
        }
    }

    /**
     * @return void
     */
    private function parseSubmitUrl(): void
    {
        /** @var string $actionName */
        $actionName = strtolower($this->request->getActionName()) . 'Post';

        /** @var string $submitUrl */
        $submitUrl = $this->data['config']['submit_url'];

        $this->data['config']['submit_url'] = str_replace(
            ':action',
            $actionName,
            $submitUrl
        );
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        /** @var array $meta */
        $meta = parent::getMeta();

        /** @var ModifierInterface[] $modifiers */
        $modifiers = $this->getModifiers();

        /** @var ModifierInterface $modifier */
        foreach ($modifiers as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var RuleInterface[] $items */
        $items = $this->getCollection()->getItems();

        /** @var RuleInterface $rule */
        foreach ($items as $rule) {
            $this->loadedData[$rule->getId()] = $rule->getData();
        }

        /** @var ModifierInterface[] $modifiers */
        $modifiers = $this->getModifiers();

        /** @var ModifierInterface $modifier */
        foreach ($modifiers as $modifier) {
            $this->loadedData = $modifier->modifyData($this->loadedData);
        }

        return $this->loadedData;
    }
}
