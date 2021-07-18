<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Ni Irrty
 * @package    Niirrty\Routing\UrlPathLocator
 * @since      2017-11-04
 * @version    0.4.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\UrlPathLocator;


/**
 * Gives you the ability to get the request URL path from $_SERVER[ 'REQUEST_URI' ]
 */
class RequestUri extends ArraySource
{


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * RequestUri constructor.
     */
    public function __construct()
    {

        parent::__construct( $_SERVER, 'REQUEST_URI' );

    }

    #endregion


    #region // – – –   P R O T E C T E D   M E T H O D S   – – – – – – – – – – – – – – – – – – – – –

    /**
     * Must be implemented  by a extending locator.
     *
     * @return string
     */
    protected function findPath(): string
    {

        $tmp = \explode( '?', parent::findPath(), 2 );

        return $tmp[ 0 ];

    }

    #endregion


}

