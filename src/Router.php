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
use \Niirrty\Routing\Redirection\MultiPath as MultiPathRedirection;
use \Niirrty\Routing\Redirection\MultiPathStatic as MultiPathStaticRedirection;
use \Niirrty\Routing\Redirection\Simple as SimpleRedirection;
use \Niirrty\Routing\Redirection\SimpleStatic as SimpleStaticRedirection;
use \Niirrty\Routing\Routes\IRoute;
use \Niirrty\Routing\Routes\MultiPath as MultiPathRoute;
use \Niirrty\Routing\Routes\Regex as RegexRoute;
use \Niirrty\Routing\Routes\Simple as SimpleRoute;
use \Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a basic router implementation.
 */
class Router implements IRouter
{


    #region // – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –

    /** @type IRoute[] */
    protected array $_routes;

    /** @type IRedirection[] */
    protected array $_redirections;

    /** @type \Closure */
    protected \Closure $_fallbackHandler;

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Router constructor. Init a empty router
     */
    public function __construct()
    {

        $this->_redirections    = [];
        $this->_routes          = [];
        $this->_fallbackHandler = function ( ILocator $locator )
        {
            throw new \InvalidArgumentException( 'Unresolved routing URL path: ' . $locator->getPath() );
        };

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Adds a new route.
     *
     * @param IRoute $route The route that should be added
     *
     * @return Router
     */
    public function addRoute( IRoute $route ): Router
    {

        $this->_routes[] = $route;

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Routes\Simple} route.
     *
     * @param string   $path
     * @param \Closure $handler
     *
     * @return Router
     */
    public function addSimpleRoute( string $path, \Closure $handler ): Router
    {

        $this->_routes[] = new SimpleRoute( $path, $handler );

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Routes\Simple} route.
     *
     * @param array    $paths
     * @param \Closure $handler
     *
     * @return Router
     */
    public function addMultiPathRoute( array $paths, \Closure $handler ): Router
    {

        $this->_routes[] = new MultiPathRoute( $paths, $handler );

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Routes\Regex} route.
     *
     * @param string     $regex
     * @param \Closure[] $handlers
     *
     * @return Router
     */
    public function addRegexRoute( string $regex, array $handlers ): Router
    {

        try { $this->_routes[] = new RegexRoute( $regex, $handlers ); }
        catch ( \Throwable ) { }

        return $this;

    }

    /**
     * Adds a new redirection.
     *
     * @param IRedirection $redirection The redirection that should be added.
     *
     * @return Router
     */
    public function addRedirection( IRedirection $redirection ): Router
    {

        $this->_redirections[] = $redirection;

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Redirection\Simple} redirection.
     *
     * @param string   $path
     * @param \Closure $handler
     *
     * @return Router
     */
    public function addSimpleRedirection( string $path, \Closure $handler ): Router
    {

        $this->_redirections[] = new SimpleRedirection( $path, $handler );

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Redirection\MultiPath} redirection.
     *
     * @param array    $paths
     * @param \Closure $handler
     *
     * @return Router
     */
    public function addMultiPathRedirection( array $paths, \Closure $handler ): Router
    {

        $this->_redirections[] = new MultiPathRedirection( $paths, $handler );

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Redirection\SimpleStatic} redirection.
     *
     * @param string $path
     * @param string $redirectionUrl
     *
     * @return Router
     */
    public function addSimpleStaticRedirection( string $path, string $redirectionUrl ): Router
    {

        $this->_redirections[] = new SimpleStaticRedirection( $path, $redirectionUrl );

        return $this;

    }

    /**
     * Adds a new {@see \Niirrty\Routing\Redirection\MultiPathStatic} redirection.
     *
     * @param array  $paths
     * @param string $redirectionUrl
     *
     * @return Router
     */
    public function addMultiPathStaticRedirection( array $paths, string $redirectionUrl ): Router
    {

        $this->_redirections[] = new MultiPathStaticRedirection( $paths, $redirectionUrl );

        return $this;

    }

    /**
     * Sets a handler that should be called if no defined route or redirection matches the current URL path.
     *
     * The assigned handler must accept a single parameter Frost\Service\UrlPathLocator\ILocator $locator
     *
     * @param \Closure $handler
     *
     * @return Router
     */
    public function setFallBackHandler( \Closure $handler ): Router
    {

        $this->_fallbackHandler = $handler;

        return $this;

    }

    /**
     * Checks if a route or redirection is registered that matches the current URL path
     *
     * @param ILocator $locator
     *
     * @return bool
     */
    public function call( ILocator $locator ): bool
    {

        foreach ( $this->_redirections as $redirection )
        {
            $redirection->call( $locator );
        }

        // Here we are only if no redirection has matched.

        foreach ( $this->_routes as $route )
        {
            if ( $route->call( $locator ) )
            {
                return true;
            }
        }

        ( $this->_fallbackHandler )( $locator );

        return false;

    }

    #endregion


    #region // – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –

    /**
     * Static constructor for fluent usage.
     *
     * @return Router
     */
    public static function CreateInstance(): Router
    {

        return new Router();

    }

    #endregion


}

