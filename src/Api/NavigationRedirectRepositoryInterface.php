<?php
namespace MX\HelperBar\Api;

interface NavigationRedirectRepositoryInterface
{
    /**
     * Return an array of \MX\HelperBar\Api\NavigationRedirectInterface objects
     *
     * @return \MX\HelperBar\Api\NavigationRedirectInterface[]
     */
    public function getRedirects();
}
