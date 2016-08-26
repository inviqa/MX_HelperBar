<?php
namespace MX\HelperBar\Model;

use MX\HelperBar\Api\NavigationRedirectInterface;
use MX\HelperBar\Api\NavigationRedirectRepositoryInterface;

class NavigationRedirectRepository implements NavigationRedirectRepositoryInterface
{
    /**
     * @var NavigationRedirectInterface[]
     */
    private $navigationRedirects;

    /**
     * NavigationRedirectRepository constructor.
     * @param array $navigationRedirects
     */
    public function __construct($navigationRedirects = [])
    {
        $this->navigationRedirects = $navigationRedirects;
        $this->validateRedirects();
    }

    /**
     * Return an array of NavigationRedirect objects
     *
     * @return NavigationRedirectInterface[]
     */
    public function getRedirects()
    {
        return $this->navigationRedirects;
    }

    private function validateRedirects()
    {
        foreach ($this->navigationRedirects as $redirect) {
            if (!$redirect instanceof NavigationRedirectInterface) {
                throw new \InvalidArgumentException(
                    "Invalid command type. Expected " . NavigationRedirectInterface::class
                );
            }
        }
    }
}
