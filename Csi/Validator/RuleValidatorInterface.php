<?php
/**
 * RuleValidatorInterface.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Csi\Validator
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Csi\Validator;

use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;

interface RuleValidatorInterface
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function validate(RuleInterface $rule): bool;
}
