<?php
namespace MX\HelperBar\Test\TestCase;

use Magento\Backend\Test\Page\Adminhtml\Dashboard;
use Magento\Mtf\TestCase\Injectable;

/**
 * Preconditions:
 * 1. Log in to Admin Index Page
 *
 * Steps:
 * 1. Verify that is displayed.
 * 2. Verify that is in developer mode
 * 3. Verify that has correct content
 * 4. Verify that has close button
 */
class HelperBarTest extends Injectable
{
    /** @var Dashboard */
    protected $backendDashboard;

    /**
     * Injecting test data
     *
     * @param Dashboard $backendDashboard
     */
    public function __inject(Dashboard $backendDashboard)
    {
        $this->backendDashboard = $backendDashboard;
    }

    /**
     * Run test
     */
    public function test()
    {
        // Preconditions
        $this->backendDashboard->open();

        // Steps
        $helperBar = $this->backendDashboard->getHelperBarBlock();

        $this->assertTrue($helperBar->isVisible());
        $this->assertTrue($helperBar->isInDeveloperMode());
        $this->assertTrue($helperBar->hasProductMetadata());
        $this->assertTrue($helperBar->hasCloseButton());
    }
}