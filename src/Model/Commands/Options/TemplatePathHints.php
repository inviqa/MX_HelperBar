<?php
namespace MX\HelperBar\Model\Commands\Options;
use \MX\HelperBar\Api\CommandOptionsInterface;

class TemplatePathHints implements CommandOptionsInterface
{
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
