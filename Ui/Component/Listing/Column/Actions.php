<?php
/**
 * Actions.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Ui\Component\Listing\Column
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

use function __;

class Actions extends Column
{
    /** @var string $paramKey */
    private $paramKey;

    /** @var string $tokenKey */
    private $tokenKey;

    /** @var UrlInterface $urlBuilder */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory,
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $paramKey
     * @param string $tokenKey
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        string $paramKey = null,
        string $tokenKey = null
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->urlBuilder = $urlBuilder;
        $this->paramKey = $paramKey ?? 'entity_id';
        $this->tokenKey = $tokenKey ?? 'token';
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            /** @var array $item */
            foreach ($dataSource['data']['items'] as &$item) {
                /** @var string $entityParam */
                $entityParam = $this->getData('config/entityParam')
                    ?? $this->paramKey;

                if (isset($item[$entityParam])) {
                    /** @var string $editUrlPath */
                    $editUrlPath = $this->getData('config/editUrlPath') ?? '#';

                    /** @var string $viewUrlPath */
                    $viewUrlPath = $this->getData('config/viewUrlPath') ?? '#';
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'hidden' => true,
                            'href' => $this->urlBuilder->getUrl(
                                $editUrlPath,
                                [$entityParam => $item[$entityParam]]
                            ),
                        ],
                        'view' => [
                            'href' => $this->urlBuilder->getUrl(
                                $viewUrlPath,
                                [$entityParam => $item[$entityParam]]
                            ),
                            'label' => __('View'),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
