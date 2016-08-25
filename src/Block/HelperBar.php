<?php
namespace MX\HelperBar\Block;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\DeploymentConfig\Reader;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\App\State;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use MX\HelperBar\Api\CommandRepositoryInterface;
use MX\HelperBar\Api\NavigationRedirectRepositoryInterface;

class HelperBar extends Template
{

    const ENVIRONMENT_NAME = 'ENVIRONMENT_NAME';
    const ENV_PARAM = 'HELPER_BAR';
    const CONFIG_DATA_PATH = "dev/debug/helper_bar_admin";

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
     * @var CommandRepositoryInterface
     */
    protected $commandRepository;

    /**
     * @var string
     */
    private $environmentName;
    /**
     * @var NavigationRedirectRepositoryInterface
     */
    private $navigationRedirectRepository;

    /**
     * HelperBar constructor.
     *
     * @param Reader $reader
     * @param ProductMetadataInterface $productMetadata
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param JsonHelper $jsonHelper
     * @param AuthorizationInterface $authorization
     * @param CommandRepositoryInterface $commandRepository
     * @param NavigationRedirectRepositoryInterface $navigationRedirectRepository
     * @param Template\Context $context
     * @param array $data
     * @param $environmentName
     */
    public function __construct(
        Reader $reader,
        ProductMetadataInterface $productMetadata,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        JsonHelper $jsonHelper,
        AuthorizationInterface $authorization,
        CommandRepositoryInterface $commandRepository,
        NavigationRedirectRepositoryInterface $navigationRedirectRepository,
        Template\Context $context,
        array $data = [],
        $environmentName
    )
    {
        $this->reader = $reader;
        $this->productMetadata = $productMetadata;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->jsonHelper = $jsonHelper;
        $this->authorization = $authorization;
        $this->commandRepository = $commandRepository;
        $this->navigationRedirectRepository = $navigationRedirectRepository;
        $this->environmentName = $environmentName;
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
     * Return the value of the
     */
    public function getEnvironmentName()
    {
        if ($this->environmentName) {
            return ucfirst($this->environmentName) . ' ' . __("Environment");
        }
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
        foreach ($this->commandRepository->getAllCommands() as $command) {
            if (!$this->authorization->isAllowed($command->getResourceId())) {
                continue;
            }
            $commands[$command->getLabel()] = [
                "url" => $command->getHandlerUrl(),
                "options" => $command->getOptions()
            ];
        }
        return $commands;
    }


    public function getRedirects()
    {
        $redirects = [];
        foreach ($this->navigationRedirectRepository->getRedirects() as $redirect) {
            if (!$this->authorization->isAllowed($redirect->getResourceId())) {
                continue;
            }
            $redirects[$redirect->getLabel()] = [
                "url" => $redirect->getUrl()
            ];
        }
        return $redirects;
    }

}
