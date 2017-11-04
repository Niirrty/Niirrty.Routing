<?php


declare( strict_types = 1 );


namespace Niirrty\Routing\Redirection;


use Niirrty\Routing\UrlPathLocator\ILocator;


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
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    */
   public function call( ILocator $locator );

   /**
    * Returns if the redirection matches the URL from defined locator.
    *
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    * @return bool
    */
   public function matches( ILocator $locator ) : bool;

}

