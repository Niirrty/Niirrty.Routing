<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Ni Irrty
 * @package    Niirrty\Routing\Redirection
 * @since      2017-11-04
 * @version    0.4.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\Redirection;


use \Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a simple redirection, matching at multiple paths.
 *
 * If the called URL path matches one of the defined paths, the defined handler is called.
 */
class MultiPath implements IRedirection
{


    #region // – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –

    /**
     * The paths that must match for trigger a redirection
     *
     * @type array
     */
    protected array $paths;

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Simple multi path redirection constructor.
     *
     * @param array    $paths   The paths that must match for trigger a redirection
     * @param \Closure $handler The \Closure that handles the redirection if it matches
     */
    public function __construct( array $paths, protected \Closure $handler )
    {

        $this->paths = [];
        foreach ( $paths as $path )
        {
            if ( null === $path )
            {
                continue;
            }
            $this->paths[] = '/' . \trim( $path, "\r\n\t /" );
        }

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Calls the route redirection with defined URL path locator URL.
     *
     * If the redirection is executed the script ends here.
     *
     * @param ILocator $locator
     */
    public function call( ILocator $locator ) : void
    {

        if ( $this->matches( $locator ) )
        {

            ( $this->handler )( $locator );
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

        return \in_array( $locator->getPath(), $this->paths, true );

    }

    #endregion


}

