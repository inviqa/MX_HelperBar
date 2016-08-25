<?php
namespace MX\HelperBar\Model\Commands\Options;

use Magento\Framework\App\Cache\TypeListInterface;
use \MX\HelperBar\Api\CommandOptionsInterface;

class ClearCache implements CommandOptionsInterface
{
    const ALL = "all";

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
        $cacheTypes[self::ALL] = "";
        foreach ($this->cacheTypeList->getTypes() as $id => $cacheType) {
            $cacheTypes[$id] = strtolower($cacheType->getCacheType());
        }

        return $cacheTypes;
    }
}
