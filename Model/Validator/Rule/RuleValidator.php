<?php
/**
 * RuleValidator.php
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

namespace AuroraExtensions\SimpleRedirects\Model\Validator\Rule;

use AuroraExtensions\SimpleRedirects\{
    Api\Data\RuleInterface,
    Component\Data\Container\DataContainerTrait,
    Csi\Data\Container\DataContainerInterface,
    Csi\Validator\RuleValidatorInterface,
    Exception\ExceptionFactory
};
use Magento\Framework\{
    App\RequestInterface,
    DataObject,
    DataObject\Factory as DataObjectFactory
};

class RuleValidator implements RuleValidatorInterface, DataContainerInterface
{
    /**
     * @property DataObject $container
     * @method DataObject|null getContainer()
     * @method DataContainerInterface setContainer()
     */
    use DataContainerTrait;

    /** @property DataObjectFactory $dataObjectFactory */
    private $dataObjectFactory;

    /** @property ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @property RequestInterface $request */
    private $request;

    /**
     * @param DataObjectFactory $dataObjectFactory
     * @param ExceptionFactory $exceptionFactory
     * @param RequestInterface $request
     * @param array $data
     * @return void
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        ExceptionFactory $exceptionFactory,
        RequestInterface $request,
        array $data = []
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->request = $request;
        $this->setContainer($dataObjectFactory->create($data));
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function validate(RuleInterface $rule): bool
    {
        /** @var string $matchType */
        $matchType = $rule->getMatchType();

        /** @var string|null $subject */
        $subject = $this->getSubjectByRuleType($rule->getRuleType());

        if ($subject !== null) {
            return $this->matchValidator
                ->validate($matchType, $subject);
        }

        return false;
    }

    /**
     * @param string $ruleType
     * @return string|null
     */
    private function getSubjectByRuleType(string $ruleType): ?string
    {
        /** @var string $method */
        $method = $this->getMethodByRuleType($ruleType);

        if ($method !== null) {
            return $this->{$method}() ?? null;
        }

        return null;
    }

    /**
     * @param string $ruleType
     * @return string|null
     */
    private function getMethodByRuleType(string $ruleType): ?string
    {
        /** @var array $methods */
        $methods = (array) $this->getContainer()
            ->getData('methods');

        return $methods[$ruleType] ?? null;
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return $this->request
            ->getPathInfo();
    }

    /**
     * @return string
     */
    private function getQuery(): string
    {
        return $this->request
            ->getQuery()
            ->toString();
    }
}
