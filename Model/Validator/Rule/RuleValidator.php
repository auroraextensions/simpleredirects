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
    Api\RuleRepositoryInterface,
    Component\Data\Container\DataContainerTrait,
    Csi\Data\Container\DataContainerInterface,
    Csi\Validator\MatchValidatorInterface,
    Csi\Validator\RuleValidatorInterface,
    Exception\ExceptionFactory
};
use Magento\Framework\{
    App\RequestInterface,
    DataObject,
    DataObject\Factory as DataObjectFactory,
    Exception\LocalizedException,
    Exception\NoSuchEntityException
};

class RuleValidator implements RuleValidatorInterface, DataContainerInterface
{
    /**
     * @property DataObject $container
     * @method DataObject|null getContainer()
     * @method DataContainerInterface setContainer()
     */
    use DataContainerTrait;

    /** @property ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @property MatchValidatorInterface $matchValidator */
    private $matchValidator;

    /** @property RequestInterface $request */
    private $request;

    /** @property RuleRepositoryInterface $ruleRepository */
    private $ruleRepository;

    /**
     * @param DataObjectFactory $dataObjectFactory
     * @param ExceptionFactory $exceptionFactory
     * @param MatchValidatorInterface $matchValidator
     * @param RequestInterface $request
     * @param RuleRepositoryInterface $ruleRepository
     * @param array $data
     * @return void
     */
    public function __construct(
        DataObjectFactory $dataObjectFactory,
        ExceptionFactory $exceptionFactory,
        MatchValidatorInterface $matchValidator,
        RequestInterface $request,
        RuleRepositoryInterface $ruleRepository,
        array $data = []
    ) {
        $this->exceptionFactory = $exceptionFactory;
        $this->matchValidator = $matchValidator;
        $this->request = $request;
        $this->ruleRepository = $ruleRepository;
        $this->setContainer($dataObjectFactory->create($data));
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function validate(RuleInterface $rule): bool
    {
        /** @var RuleInterface|null $parent */
        $parent = $this->getParentRule($rule);

        if ($parent !== null && !$this->validate($parent)) {
            return false;
        }

        return $this->isMatch($rule);
    }

    /**
     * @param RuleInterface $rule
     * @return RuleInterface|null
     */
    private function getParentRule(RuleInterface $rule): ?RuleInterface
    {
        /** @var int|null $parentId */
        $parentId = $rule->getParentId();

        if ($parentId !== null) {
            try {
                return $this->ruleRepository
                    ->getById($parentId);
            } catch (NoSuchEntityException | LocalizedException $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function isMatch(RuleInterface $rule): bool
    {
        /** @var string $matchType */
        $matchType = $rule->getMatchType();

        /** @var string $pattern */
        $pattern = $rule->getPattern();

        /** @var string|null $subject */
        $subject = $this->getSubjectByRuleType($rule->getRuleType());

        if ($subject !== null) {
            return $this->matchValidator
                ->validate($matchType, $pattern, $subject);
        }

        return false;
    }

    /**
     * @param string $ruleType
     * @return string|null
     */
    private function getSubjectByRuleType(string $ruleType): ?string
    {
        /** @var string|null $method */
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
    private function getHost(): string
    {
        return $this->request
            ->getHttpHost();
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
