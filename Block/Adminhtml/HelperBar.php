<?php namespace MX\HelperBar\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\App\DeploymentConfig\Reader;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\App\State;

class HelperBar extends Template
{
    private $reader;

    public function __construct(
        Reader $reader,
        Template\Context $context,
        array $data = []
    ) {
        $this->reader = $reader;
        parent::__construct($context, $data);
    }

    public function isEnabled()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env['HELPER_BAR']);
    }

    public function getEnv()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env['HELPER_BAR']) ? $env['HELPER_BAR'] : null;
    }

    public function getMode()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env[State::PARAM_MODE]) ? $env[State::PARAM_MODE] : null;
    }
}
