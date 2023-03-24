<?php
/**
 * ParentId.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Ui\Component\Listing\Column\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Ui\Component\Listing\Column\Rule;

use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ParentId extends Column
{
    /** @var string $entityKey */
    private $entityKey;

    /** @var RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory,
     * @param RuleRepositoryInterface $ruleRepository
     * @param array $components
     * @param array $data
     * @param string|null $entityKey
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        RuleRepositoryInterface $ruleRepository,
        array $components = [],
        array $data = [],
        string $entityKey = null
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->ruleRepository = $ruleRepository;
        $this->entityKey = $entityKey ?? 'parent_id';
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
                $value = $item[$this->entityKey] ?? null;
                $item[$this->entityKey] = $this->getLabel($value);
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
