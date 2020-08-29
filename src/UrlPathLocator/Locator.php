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


use function explode;
use function preg_replace;
use function trim;
use function urldecode;


/**
 * Defines a abstract URL path locator with all base functionality.
 */
abstract class Locator implements ILocator
{


    // <editor-fold desc="// – – –   P R O T E C T E D   F I E L D S   – – – – – – – – – – – – – – – – – – – – – –">


    /**
     * The URL path string
     *
     * @type string
     */
    protected $_path;

    /** @type array */
    protected $_pathParts;

    // </editor-fold>


    // <editor-fold desc="// – – –   P R O T E C T E D   C O N S T R U C T O R   – – – – – – – – – – – – – – – – –">

    /**
     * Locator constructor.
     */
    protected function __construct()
    {

        $this->_path = $this->cleanPath( $this->findPath() );
        $this->_pathParts = '/' === $this->_path ? [] : explode( '/', trim( $this->_path, '/' ) );

    }

    // </editor-fold>


    // <editor-fold desc="// – – –   P R O T E C T E D   M E T H O D S   – – – – – – – – – – – – – – – – – – – – –">

    /**
     * Must be implemented  by a extending locator.
     *
     * @return string
     */
    protected abstract function findPath(): string;

    protected function cleanPath( string $path ): string
    {

        return preg_replace( '~[^A-Za-z0-9/ _.:,;!$)(\]\[-]+~', '_', urldecode( $path ) );

    }

    // </editor-fold>


    // <editor-fold desc="// – – –   P U B L I C   M E T H O D S   – – – – – – – – – – – – – – – – – – – – – – – –">

    /**
     * Gets the URL path that should be used for routing.
     *
     * @return string
     */
    public function getPath(): string
    {

        return $this->_path;

    }

    /**
     * Gets the URL path parts as array.
     *
     * @return array
     */
    public function getPathParts(): array
    {

        return $this->_pathParts;

    }


    // </editor-fold>


}

