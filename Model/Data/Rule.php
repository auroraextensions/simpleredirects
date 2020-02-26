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

namespace AuroraExtensions\SimpleRedirects\Model\Data;

use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Model\ResourceModel\Rule as RuleResourceModel
};
use Magento\Framework\Model\AbstractModel;

class Rule extends AbstractModel implements RuleInterface
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(RuleResourceModel::class);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData('created_at');
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt): RuleInterface
    {
        $this->setData('created_at', $createdAt);
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getData('store_id');
    }

    /**
     * @param int $storeId
     * @return RuleInterface
     */
    public function setStoreId(int $storeId): RuleInterface
    {
        $this->setData('store_id', $storeId);
        return $this;
    }

    /**
     * @return int
     */
    public function getRedirectType(): int
    {
        return (int) $this->getData('redirect_type');
    }

    /**
     * @param int $redirectType
     * @return RuleInterface
     */
    public function setRedirectType(int $redirectType): RuleInterface
    {
        $this->setData('redirect_type', $redirectType);
        return $this;
    }

    /**
     * @return string
     */
    public function getRuleType(): string
    {
        return $this->getData('rule_type');
    }

    /**
     * @param string $ruleType
     * @return RuleInterface
     */
    public function setRuleType(string $ruleType): RuleInterface
    {
        $this->setData('rule_type', $ruleType);
        return $this;
    }

    /**
     * @return string
     */
    public function getMatchType(): string
    {
        return $this->getData('match_type');
    }

    /**
     * @param string $matchType
     * @return RuleInterface
     */
    public function setMatchType(string $matchType): RuleInterface
    {
        $this->setData('match_type', $matchType);
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->getData('pattern');
    }

    /**
     * @param string $pattern
     * @return RuleInterface
     */
    public function setPattern(string $pattern): RuleInterface
    {
        $this->setData('pattern', $pattern);
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->getData('target');
    }

    /**
     * @param string $target
     * @return RuleInterface
     */
    public function setTarget(string $target): RuleInterface
    {
        $this->setData('target', $target);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return (bool) $this->getData('is_active');
    }

    /**
     * @param bool $isActive
     * @return RuleInterface
     */
    public function setIsActive(bool $isActive): RuleInterface
    {
        $this->setData('is_active', $isActive);
        return $this;
    }
}
