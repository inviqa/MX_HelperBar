<?php
namespace MX\HelperBar\Api;

interface NavigationRedirectInterface
{
    /**
     * Text string such as 'catalog'
     *
     * @return string
     */
    public function getLabel();

    /**
     * Url used for the redirect
     *
     * @return string
     */
    public function getUrl();

    /**
     * Text string referring to a resourceId. i.e.: Magento_Backend::dev
     *
     * @return string
     */
    public function getResourceId();
}
