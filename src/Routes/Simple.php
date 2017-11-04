<?php


declare( strict_types = 1 );


namespace Niirrty\Routing\Routes;


use Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a simple route.
 *
 * A simple route means, you only have to define the URL path that must match the current URL path.
 */
class Simple implements IRoute
{


   // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * The path string that must match the URL path
    *
    * @type string
    */
   protected $_path;

   /**
    * The closure that handles the route if it matches
    *
    * @type \Closure
    */
   protected $_handler;

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

   /**
    * Simple route constructor.
    *
    * The handler callback must accept at least one parameter (ILocator instance) and must return TRUE on success,
    * FALSE otherwise.
    *
    * @param string   $path
    * @param \Closure $handler
    */
   public function __construct( string $path, \Closure $handler )
   {

      $this->_path = '/' . \trim( $path, "\r\n\t /" );
      $this->_handler = $handler;

   }

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * Calls the route with defined URL path locator URL.
    *
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    * @return bool Returns TRUE if the route matches the defined URL from locator, FALSE otherwise.
    */
   public function call( ILocator $locator ) : bool
   {

      if ( ! $this->matches( $locator ) )
      {
         return false;
      }

      return ( $this->_handler )( $locator );

   }

   /**
    * Returns if the route matches the URL from defined locator.
    *
    * @param \Niirrty\Routing\UrlPathLocator\ILocator $locator
    * @return bool
    */
   public function matches( ILocator $locator ) : bool
   {

      return $locator->getPath() === $this->_path;

   }

   // </editor-fold>


   // <editor-fold desc="// – – –   P U B L I C   S T A T I C   M E T H O D S   – – – – – – – – – – – – – – – – –">

   /**
    * Tries to parse the defined settings array to an Simple route instance
    *
    * $settings can be a numeric indicated array with the elements 0=path 1=handler
    * or an associative array with the keys, named before.
    *
    * <code>
    * [ '/', function( ILocator $urlLocator ) { … } ]
    * // or
    * [ 'path' => '/', 'handler' => function( ILocator $urlLocator ) { … } ]
    * </code>
    *
    * @param                                            $settings
    * @param \Niirrty\Routing\Routes\Simple|null $routeOut
    * @return bool
    */
   public static function TryParse( $settings, Simple &$routeOut = null ) : bool
   {

      if ( ! \is_array( $settings ) || 2 !== \count( $settings ) )
      {
         return false;
      }

      if ( isset( $settings[ 0 ], $settings[ 1 ] ) )
      {
         if ( ! \is_callable( $settings[ 1 ] ) )
         {
            return false;
         }
         $routeOut = new Simple( $settings[ 0 ], $settings[ 1 ] );
         return true;
      }

      if ( isset( $settings[ 'path' ], $settings[ 'handler' ] ) && \is_callable( $settings[ 'handler' ] ) )
      {
         $routeOut = new Simple( $settings[ 'path' ], $settings[ 'handler' ] );
         return true;
      }

      return false;

   }

   // </editor-fold>


}

