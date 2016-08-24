<?php
namespace MX\HelperBar\Api;

interface CommandRepositoryInterface
{
    /**
     * Return a list of object that implement the Command Interface
     * @return \MX\HelperBar\Api\CommandInterface[]
     */
    public function getAllCommands();
}
