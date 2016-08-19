<?php
namespace MX\HelperBar\Model;

use MX\HelperBar\Api\CommandRepositoryInterface;
use MX\HelperBar\Model\Commands;

class CommandRepository implements CommandRepositoryInterface
{
    private $commands;

    /**
     * TODO: Use Magento\Framework\Filesystem\Directory\ReadInterface to read the commands dynamically
     *
     * @param Commands\ClearCache $clearCache
     * @param Commands\TemplatePathHints $templatePathHints
     */
    public function __construct(
        Commands\ClearCache $clearCache,
        Commands\TemplatePathHints $templatePathHints
    )
    {
        $this->commands[$clearCache->getName()] = $clearCache;
        $this->commands[$templatePathHints->getName()] = $templatePathHints;
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
