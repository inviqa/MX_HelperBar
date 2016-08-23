<?php
namespace MX\HelperBar\Model\Commands;

use Magento\Framework\UrlInterface;
use MX\HelperBar\Api\CommandInterface;
use MX\HelperBar\Api\CommandOptionsInterface;

class Command implements CommandInterface
{

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    protected $name;

    protected $label;

    protected $resourceId;

    protected $handleUrl;

    /** @var  CommandOptionsInterface */
    protected $options;

    public function __construct(
        UrlInterface $urlInterface,
        $resourceId,
        $name,
        $label,
        $handleUrl,
        $options
    )
    {
        $this->urlBuilder = $urlInterface;
        $this->name = $name;
        $this->label = $label;
        $this->resourceId = $resourceId;
        $this->handleUrl = $handleUrl;
        $this->options = $options;
    }

    /**
     * Return the name for this command
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return the label for this command
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Gives the url that will handle this command
     *
     * @return string
     */
    public function getHandlerUrl()
    {
        return $this->urlBuilder->getUrl($this->handleUrl);
    }

    /**
     * Return an array where the value is displayed to the user as available option
     *
     * @return string[]
     */
    public function getOptions()
    {
        return $this->options->getOptions();
    }

    /**
     * Return the acl resource for the command
     */
    public function getResourceId()
    {
        $this->resourceId;
    }
}
