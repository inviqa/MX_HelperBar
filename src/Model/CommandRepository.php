<?php
namespace MX\HelperBar\Model;

use MX\HelperBar\Api\CommandInterface;
use MX\HelperBar\Api\CommandRepositoryInterface;

class CommandRepository implements CommandRepositoryInterface
{
    /** @var CommandInterface[] */
    private $commands;

    public function __construct($commands = [])
    {
        $this->commands = $commands;
        $this->validateCommands();
    }

    /**
     * Return a list of object that implement the Command Interface
     * @return CommandInterface[]
     */
    public function getAllCommands()
    {
        return $this->commands;
    }

    private function validateCommands()
    {
        foreach ($this->commands as $command) {
            if (!$command instanceof CommandInterface) {
                throw new \InvalidArgumentException(
                    "Invalid command type. Expected " . CommandInterface::class
                );
            }
        }
    }
}
