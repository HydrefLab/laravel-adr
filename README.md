# Action-Domain-Responder (ADR) implementation for Laravel

This package aims to deliver tools to use/implement ADR pattern with Laravel framework.

[![Travis](https://img.shields.io/travis/HydrefLab/laravel-adr.svg)](https://travis-ci.org/HydrefLab/laravel-adr)
[![Packagist](https://img.shields.io/packagist/v/hydreflab/laravel-adr.svg)](https://packagist.org/packages/hydreflab/laravel-adr)

## Installation

To install the package, run:
```bash
composer require hydreflab/laravel-adr
```
Package requires Laravel >= 5.5. 

No additional service provider registration is required as package uses auto-discovery feature.

After, run:
```bash
php artisan vendor:publish --provider=HydrefLab\\Laravel\\ADR\\ADRServiceProvider
```
to create base action class in `app\Http\Actions` directory.

## Documentation

For full documentation, please check out [WIKI](https://github.com/hydreflab/laravel-adr/wiki).

_Note:_ WIKI is still being updated. It is not yet valid reference point.

## Contributing

Contributions are welcome! Please, read [CONTRIBUTING][] for details.

## License

The package is licensed for use under the MIT License (MIT). Please, see [LICENSE][] for more information.

[contributing]: https://github.com/hydreflab/laravel-adr/blob/master/CONTRIBUTING.md
[license]: https://github.com/hydreflab/laravel-adr/blob/master/LICENSE