<?php
namespace MX\HelperBar\Model\Commands\Options;

use Magento\Framework\App\Cache\TypeListInterface;
use \MX\HelperBar\Api\CommandOptionsInterface;

class ClearCache implements CommandOptionsInterface
{
    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    public function __construct(
        TypeListInterface $cacheTypeList
    )
    {
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * Return the list of Cache Type
     */
    public function getOptions()
    {
        $cacheTypes = array();
        $cacheTypes["all"] = "All";
        foreach ($this->cacheTypeList->getTypes() as $id => $cacheType) {
            $cacheTypes[$id] = $cacheType->getCacheType();
        }

        return $cacheTypes;
    }
}
