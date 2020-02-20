<?php
/**
 * RuleRepositoryInterface.php
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

namespace AuroraExtensions\SimpleRedirects\Api;

interface RuleRepositoryInterface
{
    /**
     * @param int $id
     * @return \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): Data\RuleInterface;

    /**
     * @param \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface $rule
     * @return int
     */
    public function save(Data\RuleInterface $rule): int;

    /**
     * @param \AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface $rule
     * @return bool
     */
    public function delete(Data\RuleInterface $rule): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
