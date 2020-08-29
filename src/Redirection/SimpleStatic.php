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
use function trim;


/**
 * Defines a simple redirection. If the called URL path matches the defined path a redirection to declared redirection
 * URL is executed and the script ends.
 */
class SimpleStatic implements IRedirection
{


    // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">


    /**
     * The path that must match for trigger a redirection
     *
     * @type string
     */
    protected $_path;

    /**
     * @type string
     */
    protected $_redirectionURL;

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

    /**
     * Simple static redirection constructor.
     *
     * @param string $path
     * @param string $redirectionUrl
     */
    public function __construct( string $path, string $redirectionUrl )
    {

        $this->_path = '/' . trim( $path, "\r\n\t /" );
        $this->_redirectionURL = $redirectionUrl;

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

        return $locator->getPath() === $this->_path;

    }


    // </editor-fold>


}

