<?php

namespace spec\MX\HelperBar\Model\Commands\Options;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClearCacheSpec extends ObjectBehavior
{
    /**
     * @param \Magento\Framework\App\Cache\TypeList $typeList
     */
    function let(\Magento\Framework\App\Cache\TypeList $typeList)
    {
        $this->beConstructedWith($typeList);
    }

    function it_returns_list_of_cache_type_options(\Magento\Framework\App\Cache\TypeList $typeList)
    {
        $typeList->getTypes()->willReturn([
            'type_a' => new \Magento\Framework\DataObject(
                [
                    'id' => 'type_a',
                    'cache_type' => 'Cache Type A'
                ]),
            'type_b' => new \Magento\Framework\DataObject(
                [
                    'id' => 'type_b',
                    'cache_type' => 'Cache Type B'
                ])
        ]);
        $this->getOptions()->shouldReturn([
            \MX\HelperBar\Model\Commands\Options\ClearCache::ALL => '',
            'type_a' => 'cache type a',
            'type_b' => 'cache type b'
        ]);
    }
}
