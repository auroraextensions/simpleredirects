<?php
/**
 * RegexValidator.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\Validator\Regex
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Validator\Regex;

use AuroraExtensions\SimpleRedirects\Csi\Validator\RegexValidatorInterface;
use ErrorException;

use function preg_last_error;
use function preg_match;
use function restore_error_handler;
use function set_error_handler;

use const PREG_INTERNAL_ERROR;

class RegexValidator implements RegexValidatorInterface
{
    /**
     * @param string $pattern
     * @return bool
     */
    public function validate(string $pattern): bool
    {
        try {
            set_error_handler([$this, 'onError']);

            /** @var int|false $match */
            $match = preg_match($pattern, '');
            restore_error_handler();

            return (
                preg_last_error() !== PREG_INTERNAL_ERROR
                && $match !== false
            );
        } catch (ErrorException $e) {
            return false;
        }
    }

    /**
     * @param int $severity
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     */
    private function onError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): void {
        throw new ErrorException(
            $message,
            $severity,
            $severity,
            $file,
            $line
        );
    }
}
