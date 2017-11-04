# Niirrty.Routing

The routing library

## Installation

inside the `composer.json`:

```json
{
   "require": {
      "php": ">=7.1",
      "niirrty/niirrty.routing": "~0.1"
   }
}
```

## Usage

Routing is really simple:

First create a `.htaccess` file inside the `DOCUMENT_ROOT`.
 
```conf
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

This means: All requests to a not existing file or directory will be redirected to `index.php`
The called, not existing URL path, is passed to `$_SERVER[ 'REQUEST_URI' ]`.
 
If you want to use this package inside you're application include the depending
composer autoload.php

```php

use \Niirrty\Routing\UrlPathLocator\RequestUri as RequestUriLocator;
use \Niirrty\Routing\UrlPathLocator\ILocator;

// Get the current called not existing URL path by $_SERVER[ 'REQUEST_URI' ]
$urlPathLocator = new RequestUriLocator();

// Init the router and chain all required stuff
$router = \Niirrty\Routing\Router::CreateInstance()

   // Handling URL paths, not declared by a route
   ->setFallBackHandler( function( ILocator $locator ) use( $twig )
   {
      // TODO: add code for handling URL paths, not declared by a route
      echo 'Invalid request!';
      exit;
   } )

   // Redirect all index calls to /
   ->addMultiPathStaticRedirection(
      [ '/app.php', '/index.html', '/start.php', '/start.html', '/home.php', '/start.html' ],
      ''
   )

   ->addSimpleRoute( '/', function( ILocator $locator )
   {
      // TODO: show the home
   } )

   ->addRegexRoute(
      '~^/services/([A-Za-z0-9_.:-]+)/?$~',
      [
         function( $matches )
         {
            // TOSO: $matches[ 1 ] defines the first part inside parenthesises, and so on
            echo '<pre>';
            print_r( $matches );
            exit;
         }
      ] )

   ->addSimpleRoute( '/impressum', function( ILocator $locator )
      {
         // TODO: show the impressum
      } );

// Call the router with current locator => Done
$router->call( $urlPathLocator );
```
