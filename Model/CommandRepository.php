<?php
namespace MX\HelperBar\Model;

use MX\HelperBar\Api\CommandRepositoryInterface;
use MX\HelperBar\Model\Commands;

class CommandRepository implements CommandRepositoryInterface
{
    private $commands;

    public function __construct(
        Commands\ClearCache $clearCache
    )
    {
        $this->commands[$clearCache->getName()] = $clearCache;
    }

    /**
     * Return a list of object that implement the Command Interface
     * @return \MX\HelperBar\Api\CommandInterface[]
     */
    public function getAllCommands()
    {
        return $this->commands;
    }
}
