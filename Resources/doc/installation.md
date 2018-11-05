Installation
============

FHUserAgentBundle can be installed using Composer. Add the following repository to composer.json:

``` yaml
// composer.json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:freshheads/FHUserAgentBundle.git"
        }
    ]
}
```

Run Composer to install all required dependencies:

``` bash
composer require freshheads/user-agent-bundle
```

Add the bundle and its dependencies (if not already present) to AppKernel.php:

``` php
// in AppKernel::registerBundles() - Symfony ^3.3
$bundles = array(
    // ...
    new FH\Bundle\RestBundle\FHRestBundle(),
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
