<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the license
 * that is bundled with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@inviqa.com so we can send you a copy immediately.
 *
 * @category MX
 * @package MX\HelperBar
 * @author Alessandro Zucca <azucca@inviqa.com>
 * @copyright 2016 Inviqa
 * @license Inviqa
 * @link http://www.inviqa.com
 */
namespace MX\HelperBar\Test\Unit\Block\Adminhtml;

use \Magento\Store\Model\ScopeInterface;
use \MX\HelperBar\Block\Adminhtml\HelperBar;
use \Magento\Framework\Config\File\ConfigFilePool;
use \Magento\Framework\App\State;

use \Magento\Store\Api\Data\StoreInterface;

class HelperBarTest extends \PHPUnit_Framework_TestCase
{
    /** @var \MX\HelperBar\Block\Adminhtml\HelperBar */
    protected $helperBar;

    /** @var \Magento\Framework\App\DeploymentConfig\Reader|\PHPUnit_Framework_MockObject_MockObject */
    protected $reader;

    /** @var \Magento\Framework\App\ProductMetadataInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $productMetadataInterfaceMock;

    /** @var \Magento\Backend\Block\Template\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $context;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $scopeConfig;

    /** @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $storeManager;

    /** @var \Magento\Framework\App\Cache\TypeListInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $cacheTypeList;

    /** @var \Magento\Framework\Json\Helper\Data|\PHPUnit_Framework_MockObject_MockObject */
    protected $jsonHelper;

    /** @var \Magento\Framework\AuthorizationInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $authorization;

    /** @var \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $urlBuilder;

    protected function setUp()
    {
        $this->reader = $this->getMockBuilder('\Magento\Framework\App\DeploymentConfig\Reader')->disableOriginalConstructor()->getMock();
        $this->productMetadataInterfaceMock = $this->getMockBuilder('\Magento\Framework\App\ProductMetadataInterface')->disableOriginalConstructor()->getMock();
        $this->context = $this->getMockBuilder('\Magento\Backend\Block\Template\Context')->disableOriginalConstructor()->getMock();
        $this->scopeConfig = $this->getMockBuilder('\Magento\Framework\App\Config\ScopeConfigInterface')->disableOriginalConstructor()->getMock();
        $this->storeManager = $this->getMockBuilder('\Magento\Store\Model\StoreManagerInterface')->disableOriginalConstructor()->getMock();
        $this->cacheTypeList = $this->getMockBuilder('\Magento\Framework\App\Cache\TypeListInterface')->disableOriginalConstructor()->getMock();
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
            $this->cacheTypeList,
            $this->jsonHelper,
            $this->authorization,
            $this->context
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

    /**
     * @dataProvider isAllowed
     */
    public function testIsAllowed($expected, $isAllowedResource)
    {
        $this->authorization->expects($this->once())
            ->method('isAllowed')
            ->with(HelperBar::ADMIN_RESOURCE)
            ->will($this->returnValue($isAllowedResource));
        $this->assertSame($expected, $this->helperBar->isAllowed());
    }

    public function isAllowed()
    {
        return [
            "User is not allowed resource" => [false, false],
            "User is allowed resource" => [true, true],
        ];
    }

    public function testGetCommands()
    {
        $commandUrl = 'clear-cache-generated-url';
        $cacheTypes = [
            'type-one' => $this->getType('Type One'),
            'type-two' => $this->getType('Type Two'),
            'type-three' => $this->getType('Type Three')
        ];

        $this->urlBuilder->expects($this->any())
            ->method('getUrl')
            ->will($this->returnValue($commandUrl));

        $this->cacheTypeList->expects($this->any())
            ->method('getTypes')
            ->will($this->returnValue($cacheTypes));

        $output = $this->helperBar->getCommands();

        $this->assertSame([
            "Clear Cache" => [
                "url" => $commandUrl,
                "options" => [
                    'all' => 'All',
                    'type-one' => 'Type One',
                    'type-two' => 'Type Two',
                    'type-three' => 'Type Three'
                ]
            ]
        ], $output);
    }

    private function getType($label) {
        return new \Magento\Framework\DataObject(
            [
                'cache_type' => $label
            ]
        );
    }
}