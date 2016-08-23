<?php
namespace MX\HelperBar\Api;

interface CommandOptionsInterface
{

    /**
     * Return an array where the value is displayed to the user as available option
     *
     * @return string[]
     */
    public function getOptions();

}
