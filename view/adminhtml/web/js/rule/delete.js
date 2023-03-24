/**
 * delete.js
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT license, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://docs.auroraextensions.com/magento/extensions/2.x/simpleredirects/LICENSE.txt
 *
 * @package     AuroraExtensions\SimpleRedirects
 * @copyright   Copyright (C) 2023 Aurora Extensions <support@auroraextensions.com>
 * @license     MIT
 */
define([
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'mage/translate'
], function ($, confirm) {
    'use strict';

    /** @var {String} SELECTOR */
    var SELECTOR = '#rule-edit-delete-button';

    $(SELECTOR).on('click', function () {
        var targetUrl;

        /** @var {String} targetUrl */
        targetUrl = $(SELECTOR).data('url');

        confirm({
            'content': $.mage.__('Are you sure you want to do this?'),
            'actions': {
                confirm: function () {
                    $.ajax(targetUrl, {
                        beforeSend: function () {},
                        method: 'POST'
                    });
                }
            }
        });

        return false;
    });
});
