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
p4scu41\BaseCRUDApi\BaseCRUDApiServiceProvider::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="p4scu41\BaseCRUDApi\BaseCRUDApiServiceProvider" --tag="config"
```

### Config Vendors

    * Laravel Activitylog [https://github.com/spatie/laravel-activitylog]
        - php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
        - Take a look at config/activitylog.php before run migration
        - php artisan migrate
        - Add in .env ACTIVITY_LOGGER_ENABLED=true
    * Laravel 5 Repositories [https://github.com/andersao/l5-repository]
        - In your config/app.php add Prettus\Repository\Providers\RepositoryServiceProvider::class to the end of the providers array
        - php artisan vendor:publish --provider "Prettus\Repository\Providers\RepositoryServiceProvider"
        - Take a look at config/repository.php
    * LERN (Laravel Exception Recorder and Notifier) [https://github.com/tylercd100/lern#lern-laravel-exception-recorder-and-notifier]
        - php artisan vendor:publish --provider="Tylercd100\LERN\LERNServiceProvider"
        - Take a look at config/lern.php before run migration
        - php artisan migrate
    * Laravel Stats Tracker [https://github.com/antonioribeiro/tracker]
        - In your config/app.php add PragmaRX\Tracker\Vendor\Laravel\ServiceProvider::class to the end of the providers array
        - php artisan vendor:publish --provider=PragmaRX\\Tracker\\Vendor\\Laravel\\ServiceProvider
        - By default everything is disabled you need to decide what you want to log. Take a look at config/tracker.php and eneable some options:
        ```php
            'enabled' => true,
            'use_middleware' => true,
            'do_not_track_paths' => [
                // 'api/*',
            ],
            'do_not_track_ips' => [
                '127.0.0.0/24', // Local: range 127.0.0.1 - 127.0.0.255
                '192.168.0.0/24', // Megacable
                '192.168.1.0/24', // Infinitum
            ],
            'log_untrackable_sessions' => false,
            'log_enabled' => true,
            'console_log_enabled' => true,
            'geoip_database_path' => storage_path('geoip'),
            'log_geoip' => true,
            'log_user_agents' => true,
            'log_users' => true,
            'log_devices' => true,
            'log_languages' => true,
            'log_referers' => true,
            'log_paths' => true,
            'log_queries' => true,
            'log_routes' => true,
            'user_model' => p4scu41\BaseCRUDApi\Models\User::class,
            'stats_panel_enabled' => true,
        ```
        - Add \PragmaRX\Tracker\Vendor\Laravel\Middlewares\Tracker::class to the array $middleware in app/Http/Kernel.php
        - Create a database connection called tracker in config/database.php
        - php artisan tracker:tables
        - php artisan migrate
        - composer require geoip2/geoip2
        - php artisan tracker:updategeoip
        - sudo chmod -R 777 storage/geoip/
        - git clone https://github.com/BlackrockDigital/startbootstrap-sb-admin-2.git public/templates/sb-admin-2
        - cd public/templates/sb-admin-2
        - git checkout tags/v3.3.7+1
        - git checkout -b v3.3.7+1
        - sudo npm install -g bower
        - bower install
        - sudo npm install --global gulp-cli
        - gulp

### Configuration

In your config/app.php add p4scu41\BaseCRUDApi\Providers\ResponseMacroServiceProvider::class to the end of the providers array


If you would like to add the JWT incorporated, change all the references to the model User to p4scu41\BaseCRUDApi\Models\User::class in the directory config

## Usage

CHANGE ME

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email pasperezn@gmail.com instead of using the issue tracker.
