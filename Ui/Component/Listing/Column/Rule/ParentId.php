<?php
/**
 * ParentId.php
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

namespace AuroraExtensions\SimpleRedirects\Ui\Component\Listing\Column;

use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Api\RuleRepositoryInterface
};
use Magento\Framework\{
    Exception\LocalizedException,
    Exception\NoSuchEntityException,
    View\Element\UiComponent\ContextInterface,
    View\Element\UiComponentFactory
};
use Magento\Ui\Component\Listing\Columns\Column;

class ParentId extends Column
{
    /** @property string $paramKey */
    private $paramKey;

    /** @property RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory,
     * @param array $components
     * @param array $data
     * @param RuleRepositoryInterface $ruleRepository
     * @param string|null $paramKey
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = [],
        RuleRepositoryInterface $ruleRepository,
        string $paramKey = null
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->ruleRepository = $ruleRepository;
        $this->paramKey = $paramKey ?? 'parent_id';
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            /** @var array $item */
            foreach ($dataSource['data']['items'] as &$item) {
                /** @var string|null $value */
                $value = $item[$this->paramKey] ?? null;
                $item[$this->paramKey] = $this->getLabel($value);
            }
        }

        return $dataSource;
    }

    /**
     * @param string|null $value
     * @return string
     */
    private function getLabel(?string $value): string
    {
        if ($value !== null) {
            try {
                /** @var RuleInterface $rule */
                $rule = $this->ruleRepository
                    ->getById((int) $value);

                return $rule->getName();
            } catch (NoSuchEntityException | LocalizedException $e) {
                /* No action required. */
            }
        }

        return '---';
    }
}
