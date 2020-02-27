<?php
/**
 * MatchValidator.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Validator\Match;

use AuroraExtensions\SimpleRedirects\{
    Component\Data\Container\DataContainerTrait,
    Csi\Data\Container\DataContainerInterface,
    Csi\Validator\MatchValidatorInterface,
    Csi\Validator\RegexValidatorInterface,
    Exception\ExceptionFactory
};
use Magento\Framework\{
    App\RequestInterface,
    DataObject,
    DataObject\Factory as DataObjectFactory
};

class MatchValidator implements MatchValidatorInterface, DataContainerInterface
{
    /**
     * @property DataObject $container
     * @method DataObject|null getContainer()
     * @method DataContainerInterface setContainer()
     */
    use DataContainerTrait;

    /** @property ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @property RegexValidatorInterface $regexValidator */
    private $regexValidator;

    /** @property RequestInterface $request */
    private $request;

    /**
     * @param DataObjectFactory $dataObjectFactory
     * @param ExceptionFactory $exceptionFactory
     * @param RegexValidatorInterface $regexValidator
     * @param RequestInterface $request
     * @param array $data
     * @return void
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        ExceptionFactory $exceptionFactory,
        RegexValidatorInterface $regexValidator,
        RequestInterface $request,
        array $data = []
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->regexValidator = $regexValidator;
        $this->request = $request;
        $this->setContainer($dataObjectFactory->create($data));
    }

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
    ): bool
    {
        /** @var string|null $method */
        $method = $this->getMethodByMatchType($matchType);

        if ($method !== null) {
            return $this->{$method}($pattern, $subject);
        }

        return false;
    }

    /**
     * @param string $pattern
     * @return bool
     */
    private function isRegexValid(string $pattern): bool
    {
        return $this->regexValidator
            ->validate($pattern);
    }

    /**
     * @param string $matchType
     * @return string|null
     */
    private function getMethodByMatchType(string $matchType): ?string
    {
        /** @var array $methods */
        $methods = (array) $this->getContainer()
            ->getData('methods');

        return $methods[$matchType] ?? null;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isEqual(string $pattern, string $subject): bool
    {
        return ($pattern === $subject);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isNotEqual(string $pattern, string $subject): bool
    {
        return ($pattern !== $subject);
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isContains(string $pattern, string $subject): bool
    {
        return strpos($subject, $pattern) !== false;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isNotContains(string $pattern, string $subject): bool
    {
        return strpos($subject, $pattern) === false;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isRegexMatch(string $pattern, string $subject): bool
    {
        if (!$this->isRegexValid($pattern)) {
            return false;
        }

        /** @var int|bool|null $result */
        $result = preg_match($pattern, $subject);

        return is_int($result) ? (bool) $result : false;
    }

    /**
     * @param string $pattern
     * @param string $subject
     * @return bool
     */
    private function isNotRegexMatch(string $pattern, string $subject): bool
    {
        if (!$this->isRegexValid($pattern)) {
            return false;
        }

        /** @var int|bool|null $result */
        $result = !preg_match($pattern, $subject);

        return is_int($result) ? (bool) $result : false;
    }
}
