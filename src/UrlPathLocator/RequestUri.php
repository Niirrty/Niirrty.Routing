<?php


declare( strict_types = 1 );


namespace Niirrty\Routing\UrlPathLocator;


/**
 * Gives you the ability to get the request URL path from $_SERVER[ 'REQUEST_URI' ]
 */
class RequestUri extends ArraySource
{


   // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

   /**
    * RequestUri constructor.
    */
   public function __construct()
   {

      parent::__construct( $_SERVER, 'REQUEST_URI' );

   }

   // </editor-fold>


}

