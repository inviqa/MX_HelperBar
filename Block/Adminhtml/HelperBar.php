<?php namespace MX\HelperBar\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Framework\App\DeploymentConfig\Reader;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\App\State;

class HelperBar extends Template
{
    protected $reader;

    /**
     * HelperBar constructor.
     * @param Reader $reader
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Reader $reader,
        Template\Context $context,
        array $data = []
    ) {
        $this->reader = $reader;
        parent::__construct($context, $data);
    }

    /**
     * Check if the environment variable is set and use that to enabled / disable the module
     *
     * @return bool
     */
    public function isEnabled()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env['HELPER_BAR']);
    }

    /**
     * Return the current environment that Magento 2 is running in
     *
     * @return string | null
     */
    public function getEnv()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env['HELPER_BAR']) ? $env['HELPER_BAR'] : null;
    }

    /**
     * Return the current mode that Magento 2 is running in
     *
     * @return string | null
     */
    public function getMode()
    {
        $env = $this->reader->load(ConfigFilePool::APP_ENV);

        return isset($env[State::PARAM_MODE]) ? $env[State::PARAM_MODE] : null;
    }
}
