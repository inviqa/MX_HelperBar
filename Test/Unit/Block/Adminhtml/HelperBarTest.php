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

use \MX\HelperBar\Block\Adminhtml\HelperBar;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\App\State;

class HelperBarTest extends \PHPUnit_Framework_TestCase
{
    /** @var \MX\HelperBar\Block\Adminhtml\HelperBar */
    protected $helperBar;

    /** @var \Magento\Framework\App\DeploymentConfig\Reader|\PHPUnit_Framework_MockObject_MockObject */
    protected $readerMock;

    /** @var \Magento\Framework\App\ProductMetadataInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $productMetadataInterfaceMock;

    /** @var \Magento\Backend\Block\Template\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $contextMock;

    protected function setUp()
    {
        $this->readerMock = $this->getActualMock('\Magento\Framework\App\DeploymentConfig\Reader');
        $this->productMetadataInterfaceMock = $this->getActualMock('\Magento\Framework\App\ProductMetadataInterface');
        $this->contextMock = $this->getActualMock('\Magento\Backend\Block\Template\Context');

        $this->helperBar = new HelperBar(
            $this->readerMock,
            $this->productMetadataInterfaceMock,
            $this->contextMock
        );
    }

    private function getActualMock($originalClassName)
    {
        return $this->getMock(
            $originalClassName,
            [],
            [],
            '',
            false
        );
    }

    /**
     * @dataProvider displayValidation
     */
    public function testDisplayValidation($isEnabled, $appEnvSettings)
    {
        $this->readerMock->expects($this->once())
            ->method('load')
            ->with(ConfigFilePool::APP_ENV)
            ->will($this->returnValue($appEnvSettings));
        $this->assertSame($isEnabled, $this->helperBar->isEnabled());
    }

    public function displayValidation()
    {
        return [
            "HELPER_BAR setting missing" => [false, []],
            "HELPER_BAR setting set to true" => [true, [HelperBar::ENV_PARAM => true]],
            "HELPER_BAR setting set to false" => [false, [HelperBar::ENV_PARAM => false]]
        ];
    }

    /**
     * @dataProvider getMode
     */
    public function testGetMode($mode, $environment)
    {
        $this->readerMock->expects($this->once())
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