<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Ni Irrty
 * @package    Niirrty\Routing
 * @since      2017-11-04
 * @version    0.4.0
 */


declare( strict_types=1 );


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
     * @param IRoute $route The route that should be added
     *
     * @return IRouter
     */
    public function addRoute( IRoute $route ): IRouter;

    /**
     * Adds a new redirection.
     *
     * @param IRedirection $redirection The redirection that should be added.
     *
     * @return IRouter
     */
    public function addRedirection( IRedirection $redirection ): IRouter;

    /**
     * Sets a handler that should be called if no defined route or redirection matches the current URL path.
     *
     * The assigned handler must accept a single parameter Frost\Service\UrlPathLocator\ILocator $locator
     *
     * @param \Closure $handler
     *
     * @return IRouter
     */
    public function setFallBackHandler( \Closure $handler ): IRouter;

    /**
     * Checks if a route or redirection is registered that matches the current URL path
     *
     * @param ILocator $locator
     *
     * @return bool
     */
    public function call( ILocator $locator ): bool;


}

