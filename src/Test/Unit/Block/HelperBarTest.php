<?php
namespace MX\HelperBar\Test\Unit\Block;

use \Magento\Store\Model\ScopeInterface;
use \MX\HelperBar\Block\HelperBar;
use \Magento\Framework\Config\File\ConfigFilePool;
use \Magento\Framework\App\State;

use \Magento\Store\Api\Data\StoreInterface;

class HelperBarTest extends \PHPUnit_Framework_TestCase
{
    /** @var \MX\HelperBar\Block\HelperBar */
    protected $helperBar;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $reader;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $productMetadataInterfaceMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $context;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $scopeConfig;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $storeManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $commandRepository;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $jsonHelper;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $authorization;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $urlBuilder;

    protected function setUp()
    {
        $this->reader = $this->getMockBuilder('\Magento\Framework\App\DeploymentConfig\Reader')->disableOriginalConstructor()->getMock();
        $this->productMetadataInterfaceMock = $this->getMockBuilder('\Magento\Framework\App\ProductMetadataInterface')->disableOriginalConstructor()->getMock();
        $this->context = $this->getMockBuilder('\Magento\Backend\Block\Template\Context')->disableOriginalConstructor()->getMock();
        $this->scopeConfig = $this->getMockBuilder('\Magento\Framework\App\Config\ScopeConfigInterface')->disableOriginalConstructor()->getMock();
        $this->storeManager = $this->getMockBuilder('\Magento\Store\Model\StoreManagerInterface')->disableOriginalConstructor()->getMock();
        $this->commandRepository = $this->getMockBuilder('\MX\HelperBar\Api\CommandRepositoryInterface')->disableOriginalConstructor()->getMock();
        $this->jsonHelper = $this->getMockBuilder('\Magento\Framework\Json\Helper\Data')->disableOriginalConstructor()->getMock();
        $this->authorization = $this->getMockBuilder('\Magento\Framework\AuthorizationInterface')->disableOriginalConstructor()->getMock();
        $this->urlBuilder = $this->getMockBuilder('Magento\Framework\Url')->disableOriginalConstructor()->getMock();

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->will($this->returnValue($this->urlBuilder));

        $this->helperBar = new HelperBar(
            $this->reader,
            $this->productMetadataInterfaceMock,
            $this->scopeConfig,
            $this->storeManager,
            $this->jsonHelper,
            $this->authorization,
            $this->commandRepository,
            $this->context,
            [],
            ""
        );
    }

    /**
     * @dataProvider isEnabledDataProvider
     */
    public function testIsEnabled($isEnabled, $appEnvSettings)
    {
        $mockStore = $this->getMockBuilder('\Magento\Store\Api\Data\StoreInterface')->disableOriginalConstructor()->getMock();

        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->will($this->returnValue($mockStore));

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(HelperBar::CONFIG_DATA_PATH, ScopeInterface::SCOPE_STORE, null)
            ->will($this->returnValue($appEnvSettings));

        $this->assertSame($isEnabled, $this->helperBar->isEnabled());
    }

    public function isEnabledDataProvider()
    {
        return [
            'Setting missing' => [false, null],
            'Setting disabled' => [false, '0'],
            'Setting enabled' => [true, '1']
        ];
    }

    /**
     * @dataProvider getMode
     */
    public function testGetMode($mode, $environment)
    {
        $this->reader->expects($this->once())
            ->method('load')
            ->with(ConfigFilePool::APP_ENV)
            ->will($this->returnValue($environment));
        $this->assertSame($mode, $this->helperBar->getMode());
    }

    public function getMode()
    {
        return [
            "MAGE_MODE setting missing" => [null, null],
            "MAGE_MODE is developer" => [State::MODE_DEVELOPER, [State::PARAM_MODE => State::MODE_DEVELOPER]],
            "MAGE_MODE is default" => [State::MODE_DEFAULT, [State::PARAM_MODE => State::MODE_DEFAULT]],
            "MAGE_MODE is production" => [State::MODE_PRODUCTION, [State::PARAM_MODE => State::MODE_PRODUCTION]],
        ];
    }

    public function testGetProductMetadata()
    {
        $this->productMetadataInterfaceMock->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Magento'));
        $this->productMetadataInterfaceMock->expects($this->once())
            ->method('getEdition')
            ->will($this->returnValue('Enterprise'));
        $this->productMetadataInterfaceMock->expects($this->once())
            ->method('getVersion')
            ->will($this->returnValue('3.0.0'));
        $this->assertSame('Magento Enterprise 3.0.0', $this->helperBar->getProductMetadata());
    }
}
