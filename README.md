# LDic

[![Latest Version](https://img.shields.io/github/release/thephpleague/skeleton.svg?style=flat-square)](https://github.com/serkanerip/ldic/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/league/skeleton.svg?style=flat-square)](https://packagist.org/packages/erip/ldic)

LDic, is a lightweight dependency injection container for php. Just with 3 public functions and less than 130 lines of code.

## Install

Via Composer

``` bash
$ composer require erip/ldic
```

## Usage

``` php
class Foo {}

$container = new Erip\LDic();
$container->register(new Foo());
$productUtils = $container->resolve(Foo::class);

// Lazy Registration

// This dependency will be created only when needed.
$container->lazyRegister(function(){
    return new Foo();
});

$productUtils = $container->resolve(Foo::class);
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/serkanerip/ldic/blob/master/CONTRIBUTING.md) for details.

## Credits

- [serkanerip](https://github.com/serkanerip)
- [All Contributors](https://github.com/serkanerip/ldic/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
