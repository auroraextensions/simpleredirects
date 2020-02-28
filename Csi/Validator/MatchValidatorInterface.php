<?php
/**
 * MatchValidatorInterface.php
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

namespace AuroraExtensions\SimpleRedirects\Csi\Validator;

interface MatchValidatorInterface
{
    /**
     * @param string $matchType
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    public function validate(
        string $matchType,
        string $pattern,
        string $subject
    ): bool;
}