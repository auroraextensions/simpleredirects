<?php
/**
 * RuleInterface.php
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

namespace AuroraExtensions\SimpleRedirects\Api\Data;

interface RuleInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param \DateTime|string $createdAt
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $storeId
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setStoreId(int $storeId): RuleInterface;

    /**
     * @return int
     */
    public function getRedirectType(): int;

    /**
     * @param int $redirectType
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setRedirectType(int $redirectType): RuleInterface;

    /**
     * @return string
     */
    public function getRuleType(): string;

    /**
     * @param string $ruleType
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setRuleType(string $ruleType): RuleInterface;

    /**
     * @return string
     */
    public function getMatchType(): string;

    /**
     * @param string $matchType
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setMatchType(string $matchType): RuleInterface;

    /**
     * @return string
     */
    public function getPattern(): string;

    /**
     * @param string $pattern
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setPattern(string $pattern): RuleInterface;

    /**
     * @return string
     */
    public function getTarget(): string;

    /**
     * @param string $target
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setTarget(string $target): RuleInterface;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $token
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setToken(string $token): RuleInterface;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setPriority(int $priority): RuleInterface;

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     */
    public function setIsActive(bool $isActive): RuleInterface;
}
