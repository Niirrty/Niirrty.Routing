<?php
/**
 * @author     Ni Irrty <niirrty+code@gmail.com>
 * @copyright  © 2017-2021, Ni Irrty
 * @package    Niirrty\Routing\Routes
 * @since      2017-11-04
 * @version    0.4.0
 */


declare( strict_types=1 );


namespace Niirrty\Routing\Routes;


use \Niirrty\Routing\UrlPathLocator\ILocator;


/**
 * Defines a class that …
 *
 * Each URL path part that should be handled by a different callback should be enclosed by parenthesises.
 *
 * For example:
 *
 * if your path is /api/12.0/my-action/20
 *
 * - first path part is static 'api'
 * - second part '12.0' is changeable (version number of api)
 * - third part 'my-action' is also dynamic a action name that can change
 * - fourth part '20' is a dynamic and optional action parameter
 *
 * The regex for is can be:
 *
 * ~^/api/(\d+[\d.]+)/(\w+)/?(\d+)?$~
 *
 * If you only register a single handler it is used to handle the whole path with all parts.
 *
 * <code>
 * function( $matches )
 * {
 *    // $matches contains the following
 *    // 0 = '/api/12.0/my-action/20'
 *    // 1 = '12.0'
 *    // 2 = 'my-action'
 *    // 3 = '20'
 * }
 * </code>
 *
 * If more than one handler is registered the index of each handler inside the declaring array is associated with the
 * corresponding index from matches. If more parts are found than handlers are defined the parts without an handler are
 * ignored.
 *
 * <code>
 * [
 *    // The whole path
 *    0 => function( array $matches ) { … },
 *    // The API version number
 *    1 => function( string $versionNumber ) { … },
 *    // The action name
 *    2 => function( string $actionName ) { … },
 *    // The action parameter
 *    3 => function( string $actionParameter ) { … }
 * ]
 * </code>
 *
 * if you do not need a handler for a specific part set it to null
 *
 * <code>
 * [
 *    // Do not handle the whole path
 *    0 => null,
 *    // The API version number
 *    1 => function( string $versionNumber ) { … },
 *    // The action name
 *    2 => function( string $actionName ) { … },
 *    // The action parameter
 *    3 => function( string $actionParameter ) { … }
 * ]
 * </code>
 */
class Regex implements IRoute
{


    #region // – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –

    private array $_handlerIndexesReversed;

    private ?array $_matches;

    #endregion


    #region // – – –   P U B L I C   C O N S T R U C T O R   – – – – – – – – – – – – – – – – – – – –

    /**
     * Regex constructor.
     *
     * @param string    $regex    The regular expression that must match the checked path.
     * @param \Closure[] $handlers All handlers
     *
     * @throws \InvalidArgumentException
     */
    public function __construct( protected string $regex, protected array $handlers )
    {

        if ( ! $this->testRegularExpression( $regex ) )
        {
            throw new \InvalidArgumentException( 'Invalid regular expression passed to the regex route.' );
        }

        $hs = [];

        foreach ( $handlers as $handler )
        {
            if ( null === $handler || ! \is_callable( $handler ) )
            {
                continue;
            }
            $hs[] = $handler;
        }

        $this->handlers = $hs;

        $this->assignHandlerIndexes();

    }

    #endregion


    #region // – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –

    /**
     * Calls the route with defined URL path locator URL.
     *
     * @param ILocator $locator
     *
     * @return bool Returns TRUE if the route matches the defined URL from locator, FALSE otherwise.
     */
    public function call( ILocator $locator ): bool
    {

        if ( ! $this->matches( $locator ) )
        {
            return false;
        }

        $lastHandlerIndex = $this->_findLastHandler();

        if ( 0 > $lastHandlerIndex )
        {
            return false;
        }

        if ( 0 === $lastHandlerIndex )
        {
            $this->handlers[ 0 ]( $this->_matches );

            return true;
        }

        while ( ! isset( $this->_matches[ $lastHandlerIndex ] ) )
        {

            if ( 1 > $lastHandlerIndex )
            {
                return false;
            }

            $lastHandlerIndex = $this->_findLastHandler( $lastHandlerIndex - 1 );

            if ( 0 > $lastHandlerIndex )
            {
                return false;
            }

            if ( 0 === $lastHandlerIndex )
            {
                $this->handlers[ 0 ]( $this->_matches );

                return true;
            }

        }

        $this->handlers[ 0 ]( $this->_matches[ $lastHandlerIndex ] );

        return true;

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

        if ( \preg_match( $this->regex, $locator->getPath(), $matches ) )
        {
            $this->_matches = $matches;
            return true;
        }

        $this->_matches = [];

        return false;

    }

    /**
     * Sets a handler for specific index. If no index is defined the handler is assigned to the end.
     *
     * @param \Closure|null $handler
     * @param int|null      $index
     *
     * @return Regex
     */
    public function setHandler( ?\Closure $handler = null, ?int $index = null ): Regex
    {

        $handlerCount = \count( $this->handlers );

        if ( null === $index )
        {

            if ( 1 > $handlerCount )
            {
                $this->handlers[] = $handler;
                $this->assignHandlerIndexes();

                return $this;
            }

            $index = $this->_handlerIndexesReversed[ $handlerCount - 1 ] + 1;

        }

        $this->handlers[ $index ] = $handler;

        $this->assignHandlerIndexes();

        return $this;

    }

    #endregion


    #region // – – –   P R O T E C T E D   M E T H O D S   – – – – – – – – – – – – – – – – – – – – –

    /**
     * Checks if the defined regex is a valid, usable regex.
     *
     * @param string $regex
     *
     * @return bool
     */
    protected function testRegularExpression( string $regex ): bool
    {

        try { return false !== @\preg_match( $regex, 'foo' ); }
        catch ( \Throwable ) { return false; }

    }

    protected function assignHandlerIndexes() : void
    {

        $this->_handlerIndexesReversed = \array_reverse( \array_keys( $this->handlers ) );

    }

    #endregion


    #region // – – –   P R I V A T E   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – –

    private function _findLastHandler( ?int $startIndex = null ): int
    {

        for ( $i = 0, $c = \count( $this->_handlerIndexesReversed ); $i < $c; $i++ )
        {

            if ( ! isset( $this->handlers[ $i ] ) )
            {
                continue;
            }

            if ( null === $startIndex || $this->_handlerIndexesReversed[ $i ] <= $startIndex )
            {
                return $this->_handlerIndexesReversed[ $i ];
            }

        }

        return -1;

    }


    #endregion


}

