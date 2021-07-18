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
 * Defines a simple redirection. If the called URL path matches the defined path the defined handler is called.
 */
class Simple implements IRedirection
{


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Simple redirection constructor.
     *
     * @param string   $path    The path that must match for trigger a redirection
     * @param \Closure $handler The \Closure that handles the redirection if it matches
     */
    public function __construct( protected string $path, protected \Closure $handler )
    {

        $this->path = '/' . \trim( $this->path, "\r\n\t /" );

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
    public function call( ILocator $locator )
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

        return $locator->getPath() === $this->path;

    }

    #endregion


}

