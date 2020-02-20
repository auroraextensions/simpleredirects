/**
 * redirect.js
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
define([
    'jquery'
], function ($) {
    'use strict';

    return function () {
        $.ajaxSetup({
            /**
             * @param {Object} response
             * @return {void}
             */
            success: function (response) {
                if (response.isAjax) {
                    window.location.href = response.viewUrl;
                }
            }
        });
    };
});
