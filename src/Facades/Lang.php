<?php
/**
 *
 *
 * Author: MSkelton
 * Date: 2016-02-04
 * Change Log:
 *
 */

namespace  Waterloomatt\Translation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Waterloomatt\Translation\Translator
 */
class Lang extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'translator';
    }
}