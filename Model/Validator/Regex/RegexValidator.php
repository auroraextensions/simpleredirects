<?php
/**
 * RegexValidator.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Validator\Regex;

use ErrorException;
use AuroraExtensions\SimpleRedirects\Csi\Validator\RegexValidatorInterface;

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

            /** @var bool $isValid */
            $isValid = preg_match($pattern, '') !== false;
            restore_error_handler();

            return $isValid;
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
    ): void
    {
        throw new ErrorException(
            $message,
            $severity,
            $severity,
            $file,
            $line
        );
    }
}
