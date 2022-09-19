<?php

namespace Fliq\NftStorage\Facades;

use Illuminate\Support\Facades\Facade;

class NftStorage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'nft-storage';
    }
}
