<?php


declare( strict_types = 1 );


namespace Niirrty\Routing\UrlPathLocator;


/**
 * Each URL path locator must implement this interface
 *
 * @since v0.1.0
 */
interface ILocator
{

   /**
    * Gets the URL path that should be used for routing.
    *
    * @return string
    */
   public function getPath() : string;

   /**
    * Gets the URL path parts as array.
    *
    * @return array
    */
   public function getPathParts() : array;

}

