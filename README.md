# BaseCRUDApi

Base clases to API RESTful.

## Installation

Install via composer

```bash
composer require p4scu41/basecrudapi
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
p4scu41\BaseCRUDApi\BaseCRUDServiceProvider::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="p4scu41\BaseCRUDApi\BaseCRUDServiceProvider" --tag="config"
```

## Usage

CHANGE ME

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email pasperezn@gmail.com instead of using the issue tracker.
