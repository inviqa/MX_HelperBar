<?php
namespace MX\HelperBar\Controller\Adminhtml\Ajax\Config;

use Magento\Backend\App\Action;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Controller\Result\JsonFactory;
use MX\HelperBar\Model\Commands\Options\PlainList;

class TemplatePathHints extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Config::dev';
    
    /**
     * @var Config
     */
    private $config;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var PlainList
     */
    private $templatePathHintCommand;

    public function __construct(
        Action\Context $context,
        Config $config,
        JsonFactory $resultJsonFactory,
        PlainList $templatePathHintCommand
    )
    {
        parent::__construct($context);
        $this->config = $config;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->templatePathHintCommand = $templatePathHintCommand;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        $selectedOption = $this->getSelectedOption();
        if ($selectedOption === false) {
            return $resultJson->setData(['success' => false, 'message' => 'Option not found']);
        }

        $configurationOptions = $this->getConfigurationOptionsToSave($selectedOption);

        try {
            foreach ($configurationOptions as $path => $value) {
                $this->config->saveConfig($path, $value, 'default', 0);
            }
            $response = ['success' => true, 'message' => __('The template path hints settings have been updates successfully.')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __('An error occurred while updating the template path hints settings.')];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        return $resultJson->setData($response);
    }

    private function getSelectedOption()
    {
        $label = $this->getRequest()->getParam('labels')[0];
        $options = $this->templatePathHintCommand->getOptions();
        return array_search($label, $options);
    }

    private function getConfigurationOptionsToSave($selectedOption)
    {
        $configurationOptions = [];
        $parts = explode('_', $selectedOption);
        $area = $parts[0];
        $action = $parts[1];

        $action = ($action === 'enable') ? 1 : 0;

        if ($area == 'storefront' || $area == 'both') {
            $configurationOptions['dev/debug/template_hints_storefront'] = $action;
            $configurationOptions['dev/debug/template_hints_blocks'] = $action;
        }
        if ($area == 'admin' || $area == 'both') {
            $configurationOptions['dev/debug/template_hints_admin'] = $action;
        }

        return $configurationOptions;
    }
}
