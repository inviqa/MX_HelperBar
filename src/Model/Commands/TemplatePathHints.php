<?php
namespace MX\HelperBar\Model\Commands;

use Magento\Framework\UrlInterface;
use MX\HelperBar\Api\CommandInterface;

class TemplatePathHints implements CommandInterface
{

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlInterface
    )
    {
        $this->urlBuilder = $urlInterface;
    }

    public function getResourceId() {
        return 'Magento_Config::dev';
    }

    /**
     * Return the name for this command
     *
     * @return string
     */
    public function getName()
    {
        return 'Template Path Hints';
    }

    /**
     * Gives the url that will handle this command
     *
     * @return string
     */
    public function getHandlerUrl()
    {
        return $this->urlBuilder->getUrl('helperbar/ajax_config/templatePathHints');
    }

    /**
     * Return an array where the value is displayed to the user as available option
     *
     * @return string[]
     */
    public function getOptions()
    {
        return [
            'storefront_enable' => 'Storefront Enable',
            'storefront_disable' => 'Storefront Disable',
            'admin_enable' => 'Admin Enable',
            'admin_disable' => 'Admin Disable',
            'both_enable' => 'Both Enable',
            'both_disable' => 'Both Disable'
        ];
    }
}
