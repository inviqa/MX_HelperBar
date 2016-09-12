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

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $navigationRedirectRepository;

    protected function setUp()
    {
        $this->reader = $this->getMockBuilder('\Magento\Framework\App\DeploymentConfig\Reader')->disableOriginalConstructor()->getMock();
        $this->productMetadataInterfaceMock = $this->getMockBuilder('\Magento\Framework\App\ProductMetadataInterface')->disableOriginalConstructor()->getMock();
        $this->context = $this->getMockBuilder('\Magento\Backend\Block\Template\Context')->disableOriginalConstructor()->getMock();
        $this->scopeConfig = $this->getMockBuilder('\Magento\Framework\App\Config\ScopeConfigInterface')->disableOriginalConstructor()->getMock();
        $this->storeManager = $this->getMockBuilder('\Magento\Store\Model\StoreManagerInterface')->disableOriginalConstructor()->getMock();
        $this->commandRepository = $this->getMockBuilder('\MX\HelperBar\Api\CommandRepositoryInterface')->disableOriginalConstructor()->getMock();
        $this->navigationRedirectRepository = $this->getMockBuilder('\MX\HelperBar\Api\NavigationRedirectRepositoryInterface')->disableOriginalConstructor()->getMock();
        $this->jsonHelper = $this->getMockBuilder('\Magento\Framework\Json\Helper\Data')->disableOriginalConstructor()->getMock();
        $this->authorization = $this->getMockBuilder('\Magento\Framework\AuthorizationInterface')->disableOriginalConstructor()->getMock();
        $this->urlBuilder = $this->getMockBuilder('Magento\Framework\Url')->disableOriginalConstructor()->getMock();

        $this->context->expects($this->any())
            ->method('getUrlBuilder')
            ->willReturn($this->urlBuilder);

        $this->helperBar = new HelperBar(
            $this->reader,
            $this->productMetadataInterfaceMock,
            $this->scopeConfig,
            $this->storeManager,
            $this->jsonHelper,
            $this->authorization,
            $this->commandRepository,
            $this->navigationRedirectRepository,
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
            ->willReturn($mockStore);

        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with(HelperBar::CONFIG_DATA_PATH, ScopeInterface::SCOPE_STORE, null)
            ->willReturn($appEnvSettings);

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
            ->willReturn($environment);
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
            ->willReturn('Magento');
        $this->productMetadataInterfaceMock->expects($this->once())
            ->method('getEdition')
            ->willReturn('Enterprise');
        $this->productMetadataInterfaceMock->expects($this->once())
            ->method('getVersion')
            ->willReturn('3.0.0');
        $this->assertSame('Magento Enterprise 3.0.0', $this->helperBar->getProductMetadata());
    }

    public function testGetCommands()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mockNotAllowedCommand */
        $mockNotAllowedCommand = $this->getMockBuilder('\MX\HelperBar\Api\CommandInterface')->disableOriginalConstructor()->getMock();
        $mockNotAllowedCommand->expects($this->once())->method('getResourceId')->willReturn('a-resource-id');
        $mockNotAllowedCommand->expects($this->never())->method('getLabel');
        $mockNotAllowedCommand->expects($this->never())->method('getHandlerUrl');
        $mockNotAllowedCommand->expects($this->never())->method('getOptions');

        /** @var \PHPUnit_Framework_MockObject_MockObject $mockAllowedCommand */
        $mockAllowedCommand = $this->getMockBuilder('\MX\HelperBar\Api\CommandInterface')->disableOriginalConstructor()->getMock();
        $mockAllowedCommand->expects($this->once())->method('getResourceId')->willReturn('b-resource-id');
        $mockAllowedCommand->expects($this->once())->method('getLabel')->willReturn('b-label');
        $mockAllowedCommand->expects($this->once())->method('getHandlerUrl')->willReturn('b-url');
        $mockAllowedCommand->expects($this->once())->method('getOptions')->willReturn(['x', 'y', 'z']);

        $this->authorization->expects($this->at(0))->method('isAllowed')->with('a-resource-id')->willReturn(false);
        $this->authorization->expects($this->at(1))->method('isAllowed')->with('b-resource-id')->willReturn(true);

        $this->commandRepository->expects($this->once())
            ->method('getAllCommands')
            ->willReturn([$mockNotAllowedCommand, $mockAllowedCommand]);
        $expectedCommands = [
            'b-label' => [
                'url' => 'b-url',
                'options' => [
                    'x', 'y', 'z'
                ]
            ]
        ];
        $this->assertSame($expectedCommands, $this->helperBar->getCommands());
    }

    public function testGetRedirects()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $mockNotAllowedRedirect */
        $mockNotAllowedRedirect = $this->getMockBuilder('\MX\HelperBar\Api\NavigationRedirectInterface')->disableOriginalConstructor()->getMock();
        $mockNotAllowedRedirect->expects($this->once())->method('getResourceId')->willReturn('a-resource-id');
        $mockNotAllowedRedirect->expects($this->never())->method('getLabel');
        $mockNotAllowedRedirect->expects($this->never())->method('getUrl');

        /** @var \PHPUnit_Framework_MockObject_MockObject $mockAllowedRedirect */
        $mockAllowedRedirect = $this->getMockBuilder('\MX\HelperBar\Api\NavigationRedirectInterface')->disableOriginalConstructor()->getMock();
        $mockAllowedRedirect->expects($this->once())->method('getResourceId')->willReturn('b-resource-id');
        $mockAllowedRedirect->expects($this->once())->method('getLabel')->willReturn('b-label');
        $mockAllowedRedirect->expects($this->once())->method('getUrl')->willReturn('b-url');

        $this->authorization->expects($this->at(0))->method('isAllowed')->with('a-resource-id')->willReturn(false);
        $this->authorization->expects($this->at(1))->method('isAllowed')->with('b-resource-id')->willReturn(true);

        $this->navigationRedirectRepository->expects($this->once())
            ->method('getRedirects')
            ->willReturn([$mockNotAllowedRedirect, $mockAllowedRedirect]);
        $expectedRedirects = [
            'b-label' => [
                'url' => 'b-url'
            ]
        ];
        $this->assertSame($expectedRedirects, $this->helperBar->getRedirects());
    }

}
