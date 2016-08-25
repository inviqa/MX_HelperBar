<?php
namespace MX\HelperBar\Model\Commands\Options;

use \MX\HelperBar\Api\CommandOptionsInterface;

class PlainList implements CommandOptionsInterface
{
    /**
     * @var string[]
     */
    private $options;

    /**
     * PlainList constructor.
     * @param string[] $options
     */
    public function __construct(
        $options = []
    )
    {
        $this->options = $options;
    }

    /**
     * Return an array where the value is displayed to the user as available option
     *
     * @return string[]
     */
    public function getOptions()
    {
        return $this->options;
    }
}
