<?php
namespace MX\HelperBar\Model\Commands;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use MX\HelperBar\Api\CommandInterface;

class ClearCache implements CommandInterface
{
    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        TypeListInterface $cacheTypeList,
        UrlInterface $urlInterface
    )
    {
        $this->cacheTypeList = $cacheTypeList;
        $this->urlBuilder = $urlInterface;
    }

    public function getResourceId() {
        return 'Magento_Backend::cache';
    }

    public function getName()
    {
        return 'Clear Cache';
    }

    /**
     * Return the url to the mass refresh ajax controller
     */
    public function getHandlerUrl()
    {
        return $this->urlBuilder->getUrl('helperbar/ajax_cache/massRefresh');
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
