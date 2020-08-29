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
use function in_array;
use function trim;


/**
 * Defines a simple redirection, matching at multiple paths.
 *
 * If the called URL path matches one of the defined paths, the defined handler is called.
 */
class MultiPath implements IRedirection
{


    // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">


    /**
     * The paths that must match for trigger a redirection
     *
     * @type array
     */
    protected $_paths;

    /**
     * The closure that handles the redirection if it matches
     *
     * @type Closure
     */
    protected $_handler;

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –">

    /**
     * Simple multi path redirection constructor.
     *
     * @param array    $paths
     * @param Closure $handler
     */
    public function __construct( array $paths, Closure $handler )
    {

        $this->_paths = [];
        foreach ( $paths as $path )
        {
            if ( null === $path )
            {
                continue;
            }
            $this->_paths[] = '/' . trim( $path, "\r\n\t /" );
        }

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

        return (bool) in_array( $locator->getPath(), $this->_paths, true );

    }


    // </editor-fold>


}

