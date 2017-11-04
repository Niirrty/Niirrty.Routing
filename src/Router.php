<?php


declare( strict_types = 1 );


namespace Niirrty\Routing;


use Niirrty\Routing\Redirection\IRedirection;
use Niirrty\Routing\Redirection\MultiPath as MultiPathRedirection;
use Niirrty\Routing\Redirection\MultiPathStatic as MultiPathStaticRedirection;
use Niirrty\Routing\Redirection\Simple as SimpleRedirection;
use Niirrty\Routing\Redirection\SimpleStatic as SimpleStaticResirection;
use Niirrty\Routing\Routes\IRoute;
use Niirrty\Routing\Routes\Simple as SimpleRoute;
use Niirrty\Routing\Routes\MultiPath as MultiPathRoute;
use Niirrty\Routing\Routes\Regex as RegexRoute;
use Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a basic router implementation.
 */
class Router implements IRouter
{


   // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">

   /** @type \Niirrty\Routing\Routes\IRoute[] */
   protected $_routes;

   /** @type \Niirrty\Routing\Redirection\IRedirection[] */
   protected $_redirections;

   /** @type \Closure */
   protected $_fallbackHandler;

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

   /**
    * Router constructor. Init a empty router
    */
   public function __construct()
   {

      $this->_redirections = [];
      $this->_routes       = [];
      $this->_fallbackHandler = function( ILocator $locator )
         {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            throw new \InvalidArgumentException( 'Unresolved routing URL path: ' . $locator->getPath() );
         };

   }

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * Adds a new route.
    *
    * @param  \Niirrty\Routing\Routes\IRoute $route The route that should be added
    * @return \Niirrty\Routing\Router
    */
   public function addRoute( IRoute $route ) : Router
   {

      $this->_routes[] = $route;

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Routes\Simple} route.
    *
    * @param string   $path
    * @param \Closure $handler
    * @return \Niirrty\Routing\Router
    */
   public function addSimpleRoute( string $path, \Closure $handler ) : Router
   {

      $this->_routes[] = new SimpleRoute( $path, $handler );

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Routes\Simple} route.
    *
    * @param array   $paths
    * @param \Closure $handler
    * @return \Niirrty\Routing\Router
    */
   public function addMultiPathRoute( array $paths, \Closure $handler ) : Router
   {

      $this->_routes[] = new MultiPathRoute( $paths, $handler );

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Routes\Regex} route.
    *
    * @param string     $regex
    * @param \Closure[] $handlers
    * @return \Niirrty\Routing\Router
    */
   public function addRegexRoute( string $regex, array $handlers ) : Router
   {

      try { $this->_routes[] = new RegexRoute( $regex, $handlers ); }
      catch ( \Throwable $ex ) {}
      return $this;

   }

   /**
    * Adds a new redirection.
    *
    * @param \Niirrty\Routing\Redirection\IRedirection $redirection The redirection that should be added.
    * @return \Niirrty\Routing\Router
    */
   public function addRedirection( IRedirection $redirection ) : Router
   {

      $this->_redirections[] = $redirection;

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Redirection\Simple} redirection.
    *
    * @param string   $path
    * @param \Closure $handler
    * @return \Niirrty\Routing\Router
    */
   public function addSimpleRedirection( string $path, \Closure $handler ) : Router
   {

      $this->_redirections[] = new SimpleRedirection( $path, $handler );

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Redirection\MultiPath} redirection.
    *
    * @param array    $paths
    * @param \Closure $handler
    * @return \Niirrty\Routing\Router
    */
   public function addMultiPathRedirection( array $paths, \Closure $handler ) : Router
   {

      $this->_redirections[] = new MultiPathRedirection( $paths, $handler );

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Redirection\SimpleStatic} redirection.
    *
    * @param string $path
    * @param string $redirectionUrl
    * @return \Niirrty\Routing\Router
    */
   public function addSimpleStaticRedirection( string $path, string $redirectionUrl ) : Router
   {

      $this->_redirections[] = new SimpleStaticResirection( $path, $redirectionUrl );

      return $this;

   }

   /**
    * Adds a new {@see \Niirrty\Routing\Redirection\MultiPathStatic} redirection.
    *
    * @param array  $paths
    * @param string $redirectionUrl
    * @return \Niirrty\Routing\Router
    */
   public function addMultiPathStaticRedirection( array $paths, string $redirectionUrl ) : Router
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
    * @return \Niirrty\Routing\Router
    */
   public function setFallBackHandler( \Closure $handler ) : Router
   {

      $this->_fallbackHandler = $handler;

      return $this;

   }

   /**
    * Checks if a route or redirection is registered that matches the current URL path
    *
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    * @return bool
    */
   public function call( ILocator $locator ) : bool
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

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –">

   /**
    * Static constructor for fluent usage.
    *
    * @return \Niirrty\Routing\Router
    */
   public static function CreateInstance() : Router
   {

      return new Router();

   }

   // </editor-fold>


}

