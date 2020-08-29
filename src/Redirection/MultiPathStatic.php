<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2020, Ni Irrty
 * @package    Niirrty\Routing\Redirection
 * @since      2017-11-04
 * @version    0.3.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\Redirection;


use Niirrty\Routing\UrlPathLocator\ILocator;
use function in_array;
use function trim;


/**
 * Defines a multi path static redirection. If the called URL path matches one of the defined paths a redirection to
 * declared redirection URL is executed and the script ends.
 */
class MultiPathStatic implements IRedirection
{


    // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">


    /**
     * The paths that must match for trigger a redirection
     *
     * @type array
     */
    protected $_paths;

    /**
     * @type string
     */
    protected $_redirectionURL;

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

    /**
     * Multi path static redirection constructor.
     *
     * @param array  $paths
     * @param string $redirectionUrl
     */
    public function __construct( array $paths, string $redirectionUrl )
    {

        $this->_redirectionURL = $redirectionUrl;

        $this->_paths = [];
        foreach ( $paths as $path )
        {
            if ( null === $path )
            {
                continue;
            }
            $this->_paths[] = '/' . trim( $path, "\r\n\t /" );
        }

    }

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –">

    /**
     * Calls the route redirection with defined URL path locator URL.
     *
     * If the redirection is executed the script ends here.
     *
     * @param ILocator $locator
     */
    public function call( ILocator $locator )
    {

        if ( $this->matches( $locator ) )
        {

            header( 'Location: ' . $this->_redirectionURL );
            exit;

        }

    }

    /**
     * Returns if the route matches the URL from defined locator.
     *
     * @param ILocator $locator
     *
     * @return bool
     */
    public function matches( ILocator $locator ): bool
    {

        return in_array( $locator->getPath(), $this->_paths, true );

    }


    // </editor-fold>


}

