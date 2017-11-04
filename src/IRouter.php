<?php


declare( strict_types = 1 );


namespace Niirrty\Routing;


use \Niirrty\Routing\Redirection\IRedirection;
use \Niirrty\Routing\Routes\IRoute;
use \Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Each router must implement this interface.
 *
 * @since v0.1.0
 */
interface IRouter
{

   /**
    * Adds a new route.
    *
    * @param  \Niirrty\Routing\Routes\IRoute $route The route that should be added
    * @return \Niirrty\Routing\IRouter
    */
   public function addRoute( IRoute $route );

   /**
    * Adds a new redirection.
    *
    * @param \Niirrty\Routing\Redirection\IRedirection $redirection The redirection that should be added.
    * @return \Niirrty\Routing\IRouter
    */
   public function addRedirection( IRedirection $redirection );

   /**
    * Sets a handler that should be called if no defined route or redirection matches the current URL path.
    *
    * The assigned handler must accept a single parameter Frost\Service\UrlPathLocator\ILocator $locator
    *
    * @param \Closure $handler
    * @return \Niirrty\Routing\IRouter
    */
   public function setFallBackHandler( \Closure $handler );

   /**
    * Checks if a route or redirection is registered that matches the current URL path
    *
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    * @return bool
    */
   public function call( ILocator $locator );

}

