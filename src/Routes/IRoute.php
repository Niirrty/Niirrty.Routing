<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2020, Ni Irrty
 * @package    Niirrty\Routing\Routes
 * @since      2017-11-04
 * @version    0.3.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\Routes;


use Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Each route must be defined by a implementation of this interface
 *
 * @since v0.1.0
 */
interface IRoute
{


    /**
     * Calls the route with defined URL path locator URL.
     *
     * @param ILocator $locator
     *
     * @return bool Returns TRUE if the route matches the defined URL from locator, FALSE otherwise.
     */
    public function call( ILocator $locator ): bool;

    /**
     * Returns if the route matches the URL from defined locator.
     *
     * @param ILocator $locator
     *
     * @return bool
     */
    public function matches( ILocator $locator ): bool;


}

