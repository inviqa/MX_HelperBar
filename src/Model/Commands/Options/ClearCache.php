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
        $cacheTypeList = array_map([$this, 'getCacheTypeLowerCase'], $this->cacheTypeList->getTypes());
        return array_merge([self::ALL => ''], $cacheTypeList);
    }

    public function getCacheTypeLowerCase($cacheType)
    {
        return strtolower($cacheType->getCacheType());
    }
}
