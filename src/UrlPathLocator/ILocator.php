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
 * Each URL path locator must implement this interface
 *
 * @since v0.1.0
 */
interface ILocator
{


    /**
     * Gets the URL path that should be used for routing.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Gets the URL path parts as array.
     *
     * @return array
     */
    public function getPathParts(): array;


}

