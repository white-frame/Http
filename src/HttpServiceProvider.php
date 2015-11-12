<?php namespace WhiteFrame\Http;

use Illuminate\Support\ServiceProvider;
use Laracasts\Flash\FlashServiceProvider;

/**
 * Class HttpServiceProvider
 * @package WhiteFrame\Http
 */
class HttpServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Register 3rd party providers
        $this->app->register(FlashServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}