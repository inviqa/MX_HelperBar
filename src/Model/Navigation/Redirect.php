<?php
namespace MX\HelperBar\Model\Navigation;

use Magento\Framework\UrlInterface;

class Redirect implements \MX\HelperBar\Api\NavigationRedirectInterface
{
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $resourceId;
    /**
     * @var UrlInterface
     */
    private $url;

    const NAVIGATION_PREFIX = 'nav';

    /**
     * Redirect constructor.
     * @param UrlInterface $url
     * @param string $label
     * @param string $path
     * @param string $resourceId
     */
    public function __construct(
        UrlInterface $url,
        $label,
        $path,
        $resourceId
    )
    {
        $this->url = $url;
        $this->label = $label;
        $this->path = $path;
        $this->resourceId = $resourceId;
    }

    /**
     * Text string such as 'catalog'
     *
     * @return string
     */
    public function getLabel()
    {
        return self::NAVIGATION_PREFIX . ' ' . $this->label;
    }

    /**
     * Text string referring to a path in magento. i.e.: catalog/index/view
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url->getUrl($this->path);
    }

    /**
     * Text string referring to a resourceId. i.e.: Magento_Backend::dev
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }
}
