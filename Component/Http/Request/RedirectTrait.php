<?php
/**
 * RedirectTrait.php
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

namespace AuroraExtensions\SimpleRedirects\Component\Http\Request;

use Magento\Framework\Controller\Result\Redirect;

/**
 * @api
 * @since 1.0.0
 */
trait RedirectTrait
{
    /**
     * @return Redirect
     */
    public function getRedirect(): Redirect
    {
        return $this->resultRedirectFactory->create();
    }

    /**
     * @param string $path
     * @return Redirect
     */
    public function getRedirectToPath(string $path = '*'): Redirect
    {
        /** @var Redirect $redirect */
        $redirect = $this->getRedirect();
        $redirect->setPath($path);

        return $redirect;
    }

    /**
     * @param string $url
     * @return Redirect
     */
    public function getRedirectToUrl(string $url = '*'): Redirect
    {
        /** @var Redirect $redirect */
        $redirect = $this->getRedirect();
        $redirect->setUrl($url);

        return $redirect;
    }
}
