<?php
namespace MX\HelperBar\Model;

use MX\HelperBar\Api\CommandRepositoryInterface;
use \Magento\Framework\Module\Dir;

class CommandRepository implements CommandRepositoryInterface
{
    /** @var \MX\HelperBar\Model\Config\Reader */
    protected $reader;

    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /** @var \MX\HelperBar\Api\CommandInterface[] */
    private $commands;

    public function __construct(
        \MX\HelperBar\Model\Config\Reader $reader,
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->reader = $reader;
        $this->objectManager = $objectManager;
    }

    /**
     * Return a list of object that implement the Command Interface
     * @return \MX\HelperBar\Api\CommandInterface[]
     */
    public function getAllCommands()
    {
        if (!isset($this->commands)) {
            $this->loadCommands();
        }
        return $this->commands;
    }

    private function loadCommands()
    {
        $commands = $this->reader->read();
        foreach ($commands as $name => $command) {
            $this->commands[$name] = $this->objectManager->create('\MX\HelperBar\Model\Commands\Command', [
                'resourceId' => $command['resource'],
                'name' => $name,
                'label' => $command['label'],
                'handleUrl' => $command['handleUrl'],
                'options' => $this->objectManager->create($command['options'])
            ]);
        }
    }
}
