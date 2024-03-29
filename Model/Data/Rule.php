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
 * @package     AuroraExtensions\SimpleRedirects\Model\Data
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Data;

use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Model\ResourceModel\Rule as RuleResource;
use Magento\Framework\Model\AbstractModel;

class Rule extends AbstractModel implements RuleInterface
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(RuleResource::class);
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
     * @return string
     */
    public function getName(): string
    {
        return $this->getData('name');
    }

    /**
     * @param string $name
     * @return RuleInterface
     */
    public function setName(string $name): RuleInterface
    {
        $this->setData('name', $name);
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
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return (int) $this->getData('parent_id') ?: null;
    }

    /**
     * @param int|null $parentId
     * @return RuleInterface
     */
    public function setParentId(?int $parentId): RuleInterface
    {
        $this->setData('parent_id', $parentId);
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
     * @return string|null
     */
    public function getTarget(): ?string
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
     * @return string
     */
    public function getToken(): string
    {
        return $this->getData('token');
    }

    /**
     * @param string $token
     * @return RuleInterface
     */
    public function setToken(string $token): RuleInterface
    {
        $this->setData('token', $token);
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return (int) $this->getData('priority');
    }

    /**
     * @param int $priority
     * @return RuleInterface
     */
    public function setPriority(int $priority): RuleInterface
    {
        $this->setData('priority', $priority);
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
