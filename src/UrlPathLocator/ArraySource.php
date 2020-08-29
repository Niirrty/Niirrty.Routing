<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2020, Ni Irrty
 * @package    Niirrty\Routing\UrlPathLocator
 * @since      2017-11-04
 * @version    0.3.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\UrlPathLocator;


use function trim;


/**
 * Gives you the ability to get the request URL path from specified array source
 */
class ArraySource extends Locator
{


    // <editor-fold desc="// – – –   P R I V A T E   F I E L D S   – – – – – – – – – – – – – – – – – – – – – – – –">

    private $_source;

    private $_key;

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

    /**
     * ArraySource constructor.
     *
     * @param array  $source
     * @param string $arrayKey
     */
    public function __construct( array $source, string $arrayKey )
    {

        $this->_source = $source;
        $this->_key = $arrayKey;

        parent::__construct();

    }

    // </editor-fold>


    // <editor-fold desc="// – – –   P R O T E C T E D   M E T H O D S   – – – – – – – – – – – – – – – – – – – – –">

    /**
     * Must be implemented  by a extending locator.
     *
     * @return string
     */
    protected function findPath(): string
    {

        if ( !isset( $this->_source[ $this->_key ] ) )
        {
            return '/';
        }
        $uri = trim( $this->_source[ $this->_key ], ' /' );

        if ( '' === $uri )
        {
            $uri = '/';
        }
        else
        {
            $uri = '/' . $uri;
        }

        return $uri;

    }


    // </editor-fold>


}

