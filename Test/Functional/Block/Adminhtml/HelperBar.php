<?php
namespace MX\HelperBar\Test\Block\Adminhtml;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

class HelperBar extends Block
{
    const MODE_DEVELOPER = ".mage-mode-developer";

    const PRODUCT_METADATA = "//span[contains(text(), \"Magento\") and contains(text(), \"Powered by\")]";

    const CLOSE_BUTTON = ".helper-bar-close";

    /**
     * Check if the block is in developer mode
     *
     * @return bool
     */
    public function isInDeveloperMode()
    {
        return $this->_rootElement->find(self::MODE_DEVELOPER)->isVisible();
    }

    /**
     * Checks if the block has the product metadata
     *
     * @return bool
     */
    public function hasProductMetadata()
    {
        return $this->_rootElement->find(self::PRODUCT_METADATA, Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Checks if the block has the close button
     *
     * @return bool
     */
    public function hasCloseButton()
    {
        return $this->_rootElement->find(self::CLOSE_BUTTON)->isVisible();
    }
}