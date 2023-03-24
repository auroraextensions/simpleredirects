<?php
/**
 * Edit.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects\Block\Adminhtml\Rule
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
declare(strict_types=1);

namespace AuroraExtensions\SimpleRedirects\Block\Adminhtml\Rule;

use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;

use function __;

class Edit extends Container
{
    /** @var string $_blockGroup */
    protected $_blockGroup = 'AuroraExtensions_SimpleRedirects';

    /**
     * @param Context $context
     * @param array $data
     * @return void
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_objectId = 'simpleredirects_rule_edit';
        $this->_controller = 'adminhtml_rule';
        $this->setId('simpleredirects_rule_edit');

        $this->addButton(
            'simpleredirects_rule_edit',
            [
                'class' => 'edit primary',
                'id' => 'simpleredirects-rule-edit',
                'label' => __('Edit'),
                'onclick' => $this->getOnClickJs() ?? '',
            ]
        );
    }

    /**
     * @return string|null
     */
    private function getOnClickJs(): ?string
    {
        /** @var int|null $ruleId */
        $ruleId = (int) $this->getRequest()->getParam('rule_id') ?: null;

        if ($ruleId !== null) {
            /** @var string $targetUrl */
            $targetUrl = $this->getUrl(
                'simpleredirects/rule/edit',
                [
                    'rule_id' => $ruleId,
                    '_secure' => true,
                ]
            );
            return "(function(){window.location='{$targetUrl}';})();";
        }

        return null;
    }
}
