<?php namespace WhiteFrame\Http;

use Illuminate\Support\ServiceProvider;
use WhiteFrame\Support\Framework;

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
        Framework::registerPackage('http');

        // Register the exception handler
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \WhiteFrame\Http\ExceptionHandler::class
        );

        // Register the message handler
        $this->app->singleton(
            \WhiteFrame\Http\Contracts\MessageHandler::class,
            \WhiteFrame\Http\SessionMessageHandler::class
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