<?php


declare( strict_types = 1 );


namespace Niirrty\Routing\Routes;


use Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a multi path route.
 *
 * A multi path route means, you only have to define the URL paths that must match the current URL path.
 */
class MultiPath implements IRoute
{


   // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">

   /**
    * The paths strings that must match the URL path
    *
    * @type array
    */
   protected $_paths;

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
    * @param array    $paths
    * @param \Closure $handler
    */
   public function __construct( array $paths, \Closure $handler )
   {

      $this->_paths = [];
      foreach ( $paths as $path )
      {
         if ( null === $path )
         {
            continue;
         }
         $this->_paths[] = '/' . \trim( $path, "\r\n\t /" );
      }

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

      return \in_array( $locator->getPath(), $this->_paths, true );

   }

   // </editor-fold>


}

