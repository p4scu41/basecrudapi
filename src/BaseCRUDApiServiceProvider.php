<?php

namespace p4scu41\BaseCRUDApi;

use Illuminate\Support\ServiceProvider;

/**
 * Providers Base Class
 *
 * @category Providers
 * @package  p4scu41\BaseCRUDApi
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @see http://laravel.com/docs/master/packages#service-providers
 * @see http://laravel.com/docs/master/providers
 * @created  2018-04-04
 */
class BaseCRUDApiServiceProvider extends ServiceProvider
{
    const PACKAGE_NAME = 'basecrudapi';

    /**
     * Bootstrap services.
     *
     * @see http://laravel.com/docs/master/providers#the-boot-method
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();
        $this->registerSeeds();
        $this->registerTranslations();
        $this->registerConfigurations();
        $this->registerMiddlewares();

        if (!$this->app->routesAreCached()) {
            $this->registerRoutes();
        }

        if (!$this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * Register services.
     *
     * @see http://laravel.com/docs/master/providers#the-register-method
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the package migrations
     *
     * @see http://laravel.com/docs/master/packages#publishing-file-groups
     * @return void
     */
    protected function registerMigrations()
    {
        $this->publishes([
            $this->packagePath('database/migrations') => database_path('/migrations')
        ], 'migrations');
    }

    /**
     * Register the package database seeds
     *
     * @return void
     */
    protected function registerSeeds()
    {
        $this->publishes([
            $this->packagePath('database/seeds') => database_path('/seeds')
        ], 'seeds');
    }

    /**
     * Register the package translations
     *
     * @see http://laravel.com/docs/master/packages#translations
     * @return void
     */
    protected function registerTranslations()
    {
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), self::PACKAGE_NAME);
    }

    /**
     * Register the package configurations
     *
     * @see http://laravel.com/docs/master/packages#configuration
     * @return void
     */
    protected function registerConfigurations()
    {
        $this->mergeConfigFrom(
            $this->packagePath('config/' . self::PACKAGE_NAME . '.php'), self::PACKAGE_NAME
        );
        $this->publishes([
            $this->packagePath('config/' . self::PACKAGE_NAME . '.php') => config_path(self::PACKAGE_NAME . '.php'),
        ], 'config');
    }

    /**
     * Register the package routes
     *
     * @warn consider allowing routes to be disabled
     * @see http://laravel.com/docs/master/routing
     * @see http://laravel.com/docs/master/packages#routing
     * @return void
     */
    protected function registerRoutes()
    {
        $this->app->router->group(
            [
                'namespace' => __NAMESPACE__ . '\Http\Controllers',
                'middleware' => 'web',
            ],
            function () {
                require __DIR__ . '/../routes/web.php';
            }
        );

        $this->app->router->group(
            [
                'namespace' => __NAMESPACE__ . '\Http\Controllers\Api\V1',
                'as'         => 'api.v1.',
                'prefix'     => 'api/v1',
                'middleware' => 'api',
            ],
            function () {
                require __DIR__ . '/../routes/api.php';
            }
        );
    }

    /**
     * Register Artisan commands
     *
     * @see https://laravel.com/docs/master/packages#commands
     * @return void
     */
    protected function registerMiddlewares()
    {
        $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];

        $kernel->pushMiddleware(__NAMESPACE__ . '\Http\Middleware\BaseMiddleware');
    }

    /**
     * Register Artisan commands
     *
     * @see https://laravel.com/docs/master/packages#commands
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            //
        ]);
    }

    /**
     * Loads a path relative to the package base directory
     *
     * @param string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        return sprintf("%s/../%s", __DIR__, $path);
    }
}
