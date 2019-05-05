# Guide

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/guide.svg)](https://packagist.org/packages/endroid/guide)
[![Build Status](http://img.shields.io/travis/endroid/guide.svg)](http://travis-ci.org/endroid/guide)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/guide.svg)](https://packagist.org/packages/endroid/guide)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/guide.svg)](https://packagist.org/packages/endroid/guide)
[![License](http://img.shields.io/packagist/l/endroid/guide.svg)](https://packagist.org/packages/endroid/guide)

This library helps you building a TV guide from different sources.

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/guide
```

## Symfony integration

Register the Symfony bundle in the kernel.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\Guide\Bundle\GuideBundle\EndroidGuideBundle(),
    ];
}
```

The bundle makes use of different loaders to build a guide. Each loader having
its own configuration. This is an example of such a configuration.

```yaml
endroid_guide:
    shows:
        -
            type: epguides
            label: Fargo
            url: http://epguides.com/Fargo/
        -
            type: epguides
            label: Homeland
            url: http://epguides.com/Homeland/
        -
            type: npo
            label: Zondag met Lubach
```

Add the following section to your routing to be able to visit the guide.

``` yml
endroid_guide:
    resource: "@EndroidGuideBundle/Resources/config/routes.yaml"
    prefix: /guide
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
