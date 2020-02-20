<?php
/**
 * ExceptionFactory.php
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

namespace AuroraExtensions\SimpleRedirects\Exception;

use Exception, Throwable;
use Magento\Framework\{
    ObjectManagerInterface,
    Phrase
};

class ExceptionFactory
{
    /** @constant string BASE_TYPE */
    public const BASE_TYPE = Exception::class;

    /** @constant string ERROR_DEFAULT_MSG */
    public const ERROR_DEFAULT_MSG = 'An error occurred. Unable to process the request.';

    /** @constant string ERROR_INVALID_TYPE */
    public const ERROR_INVALID_TYPE = 'Invalid exception class type %1 was given.';

    /** @property ObjectManagerInterface $objectManager */
    protected $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @return void
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string|null $type
     * @param Phrase|null $message
     * @return Throwable
     * @throws Exception
     */
    public function create(
        string $type = self::BASE_TYPE,
        Phrase $message = null
    ) {
        /** @var array $arguments */
        $arguments = [];

        /* Set default message, as required. */
        $message = $message ?? __(static::ERROR_DEFAULT_MSG);

        if (!is_subclass_of($type, Throwable::class)) {
            throw new Exception(
                __(
                    static::ERROR_INVALID_TYPE,
                    $type
                )->__toString()
            );
        }

        if ($type !== static::BASE_TYPE) {
            $arguments['phrase'] = $message;
        } else {
            $arguments['message'] = $message->__toString();
        }

        return $this->objectManager->create($type, $arguments);
    }
}
