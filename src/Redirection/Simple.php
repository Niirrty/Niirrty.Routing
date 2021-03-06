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


use Closure;
use Niirrty\Routing\UrlPathLocator\ILocator;
use function trim;


/**
 * Defines a simple redirection. If the called URL path matches the defined path the defined handler is called.
 */
class Simple implements IRedirection
{


    // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">


    /**
     * The path that must match for trigger a redirection
     *
     * @type string
     */
    protected $_path;

    /**
     * The closure that handles the redirection if it matches
     *
     * @type Closure
     */
    protected $_handler;

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

    /**
     * Simple redirection constructor.
     *
     * @param string   $path
     * @param Closure $handler
     */
    public function __construct( string $path, Closure $handler )
    {

        $this->_path = '/' . trim( $path, "\r\n\t /" );
        $this->_handler = $handler;

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

            ( $this->_handler )( $locator );
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

