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

use Magento\Framework\Config\File\ConfigFilePool;

class HelperBar extends \PHPUnit_Framework_TestCase
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

        $this->helperBar = new \MX\HelperBar\Block\Adminhtml\HelperBar(
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
     * @dataProvider displayHelpBarWhenIsEnabledInApplicationSettingsDataProvider
     */
    public function testDisplayHelpBarWhenIsEnabledInApplicationSettings($isEnabledExpected, $helperBarSetting)
    {
        if (is_null($helperBarSetting)) {
            $mockAppEnvConfig = [];
        } else {
            $mockAppEnvConfig = ["HELPER_BAR" => $helperBarSetting];
        }
        $this->readerMock->expects($this->once())
            ->method('load')
            ->with(ConfigFilePool::APP_ENV)
            ->will($this->returnValue($mockAppEnvConfig));
        $this->assertSame($isEnabledExpected, $this->helperBar->isEnabled($isEnabledExpected));
    }

    public function displayHelpBarWhenIsEnabledInApplicationSettingsDataProvider()
    {
        return [
            "HELPER_BAR setting missing" => [false, null],
            "HELPER_BAR setting set to true" => [true, true],
            "HELPER_BAR setting set to false" => [false, false]
        ];
    }
}