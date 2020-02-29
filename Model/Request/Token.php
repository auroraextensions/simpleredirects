<?php
/**
 * Token.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Request;

class Token
{
    /** @constant int MIN_BYTES */
    public const MIN_BYTES = 4;

    /**
     * @param int $length
     * @return string
     */
    public static function generate(
        int $length = self::MIN_BYTES
    ): string
    {
        /* Enforce minimum length requirement. */
        $length = $length < self::MIN_BYTES
            ? self::MIN_BYTES
            : $length;

        return bin2hex(random_bytes($length));
    }
}
