<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2026, Ni Irrty
 * @package    Niirrty\Routing\Redirection
 * @since      2017-11-04
 * @version    0.6.4
 */


declare( strict_types=1 );


namespace Niirrty\Routing\Redirection;


use \Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Each routing redirection implementation must implement this interface
 *
 * @since v0.1.0
 */
interface IRedirection
{


    /**
     * Calls the redirection with defined URL path locator URL.
     *
     * If the redirection matches the declared URL path the script is finished after this method call.
     *
     * @param ILocator $locator
     */
    public function call( ILocator $locator );

    /**
     * Returns if the redirection matches the URL from defined locator.
     *
     * @param ILocator $locator
     *
     * @return bool
     */
    public function matches( ILocator $locator ): bool;


}

