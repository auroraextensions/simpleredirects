<?php
/**
 * RuleValidator.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Model\Validator\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Model\Validator\Rule;

use AuroraExtensions\ModuleComponents\Component\Data\Container\DataContainerTrait;
use AuroraExtensions\ModuleComponents\Exception\ExceptionFactory;
use AuroraExtensions\SimpleRedirects\Api\Data\RuleInterface;
use AuroraExtensions\SimpleRedirects\Api\RuleRepositoryInterface;
use AuroraExtensions\SimpleRedirects\Csi\Validator\MatchValidatorInterface;
use AuroraExtensions\SimpleRedirects\Csi\Validator\RuleValidatorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\Factory as DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class RuleValidator implements RuleValidatorInterface
{
    /**
     * @var DataObject $container
     * @method DataObject|null getContainer()
     * @method $this setContainer()
     */
    use DataContainerTrait;

    /** @var ExceptionFactory $exceptionFactory */
    private $exceptionFactory;

    /** @var MatchValidatorInterface $matchValidator */
    private $matchValidator;

    /** @var RequestInterface $request */
    private $request;

    /** @var RuleRepositoryInterface $ruleRepository */
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
     * {@inheritdoc}
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
        try {
            /** @var int|null $parentId */
            $parentId = $rule->getParentId();
            return $parentId !== null
                ? $this->ruleRepository->getById($parentId) : null;
        } catch (NoSuchEntityException | LocalizedException $e) {
            return null;
        }
    }

    /**
     * @param RuleInterface $rule
     * @return bool
     */
    private function isMatch(RuleInterface $rule): bool
    {
        /** @var string|null $subject */
        $subject = $this->getSubjectByRuleType($rule->getRuleType());

        if ($subject === null) {
            return false;
        }

        return $this->matchValidator->validate(
            $rule->getMatchType(),
            $rule->getPattern(),
            $subject
        );
    }

    /**
     * @param string $ruleType
     * @return string|null
     */
    private function getSubjectByRuleType(string $ruleType): ?string
    {
        /** @var string|null $method */
        $method = $this->getMethodByRuleType($ruleType);
        return $method !== null
            ? $this->{$method}() : null;
    }

    /**
     * @param string $ruleType
     * @return string|null
     */
    private function getMethodByRuleType(string $ruleType): ?string
    {
        /** @var array $methods */
        $methods = (array) $this->getContainer()->getData('methods');
        return $methods[$ruleType] ?? null;
    }

    /**
     * @return string
     */
    private function getHost(): string
    {
        return $this->request->getHttpHost();
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * @return string
     */
    private function getQuery(): string
    {
        return $this->request->getQuery()->toString();
    }
}
