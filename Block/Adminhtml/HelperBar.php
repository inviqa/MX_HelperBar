<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the license
 * that is bundled with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@sessiondigital.com so we can send you a copy immediately.
 *
 * @category MX
 * @package MX\HelperBar
 * @author James Cowie <jcowie@sessiondigital.com>
 * @copyright 2016 Session Digital
 * @license Session Digital
 * @link http://sessiondigital.com
 */
namespace MX\HelperBar\Block\Adminhtml;

/**
 * @category MX
 * @package MX\HelperBar
 * @subpackage MX\HelperBar
 * @author James Cowie <jcowie@sessiondigital.com>
 * @copyright 2016 Session Digital
 * @license Session Digital
 * @link http://sessiondigital.com
 */
use Magento\Backend\Block\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig\Reader;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\App\State;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use MX\HelperBar\Model\CommandRepository;

class HelperBar extends Template
{
    const ENV_PARAM = 'HELPER_BAR';
    const CONFIG_DATA_PATH = "dev/debug/helper_bar_admin";
    const ADMIN_RESOURCE = 'Magento_Backend::admin';

    /**
     * @var Reader $reader
     */
    protected $reader;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * @var CommandRepository
     */
    protected $commandRepository;

    /**
     * HelperBar constructor.
     *
     * @param Reader $reader
     * @param ProductMetadataInterface $productMetadata
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param JsonHelper $jsonHelper
     * @param AuthorizationInterface $authorization
     * @param CommandRepository $commandRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Reader $reader,
        ProductMetadataInterface $productMetadata,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        JsonHelper $jsonHelper,
        AuthorizationInterface $authorization,
        CommandRepository $commandRepository,

        Template\Context $context,
        array $data = []
    )
    {
        $this->reader = $reader;
        $this->productMetadata = $productMetadata;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->jsonHelper = $jsonHelper;
        $this->authorization = $authorization;
        $this->commandRepository = $commandRepository;
        parent::__construct($context, $data);
    }

    /**
     * Check if the environment variable is set and use that to enabled / disable the module
     *
     * @return bool
     */
    public function isEnabled()
    {
        $storeCode = $this->storeManager->getStore()->getCode();
        return $this->scopeConfig->getValue(self::CONFIG_DATA_PATH, ScopeInterface::SCOPE_STORE, $storeCode) === '1';
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->authorization->isAllowed(self::ADMIN_RESOURCE);
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

    /**
     * Return the framework name, edition and the version
     *
     * @return string
     */
    public function getProductMetadata()
    {
        return sprintf("%s %s %s",
            $this->productMetadata->getName(),
            $this->productMetadata->getEdition(),
            $this->productMetadata->getVersion());
    }

    public function getCommands()
    {
        $commands = [];
        foreach ($this->commandRepository->getAllCommands() as $name => $command) {
            $commands[$name] = [
                "url" => $command->getHandlerUrl(),
                "options" => $command->getOptions()
            ];
        }
        return $commands;
    }

}
