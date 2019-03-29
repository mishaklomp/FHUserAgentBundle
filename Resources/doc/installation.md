Installation
============

FHUserAgentBundle can be installed using Composer.

Run Composer to install all required dependencies:

``` bash
composer require freshheads/user-agent-bundle
```

Add the bundle and its dependencies (if not already present) to AppKernel.php:

``` php
// in AppKernel::registerBundles() - Symfony ^3.4
$bundles = array(
    // ...
    new FH\Bundle\UserAgentBundle\FHUserAgentBundle(),
    // ...
);

// in bundles.php - Symfony ^4.0
return [
    // ...
    FH\Bundle\UserAgentBundle\FHUserAgentBundle::class => ['all' => true],
    // ...
];
```

Now the bundle is ready to use!
