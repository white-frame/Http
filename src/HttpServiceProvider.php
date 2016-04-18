<?php namespace WhiteFrame\Http;

use Illuminate\Support\ServiceProvider;

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
        // Handling errors
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \WhiteFrame\Http\Exceptions\Handler::class
        );
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