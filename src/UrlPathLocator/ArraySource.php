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
 * Gives you the ability to get the request URL path from specified array source
 */
class ArraySource extends Locator
{


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * ArraySource constructor.
     *
     * @param array  $source
     * @param string $key
     */
    public function __construct( private readonly array $source, private readonly string $key )
    {

        parent::__construct();

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

        if ( ! isset( $this->source[ $this->key ] ) )
        {
            return '/';
        }
        $uri = \trim( $this->source[ $this->key ], ' /' );

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

    #endregion


}

