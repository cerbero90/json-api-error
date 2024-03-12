<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Providers;

use Cerbero\JsonApiError\Contracts\JsonApiErrorsAware;
use Cerbero\JsonApiError\Contracts\JsonApiRenderable;
use Cerbero\JsonApiError\Exceptions\JsonApiException;
use Cerbero\JsonApiError\JsonApiError;
use Cerbero\JsonApiError\Services\TestResponseMixin;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Throwable;

/**
 * The package service provider.
 */
final class JsonApiErrorServiceProvider extends ServiceProvider
{
    /**
     * The name of the package.
     */
    public const NAME = 'json-api-error';

    /**
     * The path to the translation files.
     */
    public const PATH_LANG = __DIR__ . '/../../lang';

    /**
     * Bootstrap the package services.
     *
     * @param \Illuminate\Foundation\Exceptions\Handler $handler
     */
    public function boot(ExceptionHandler $handler): void
    {
        $handler->renderable(function (Throwable $e, Request $request) {
            return $request->expectsJson() ? JsonApiError::from($e)->response() : null;
        });

        $handler->reportable(function (JsonApiException|JsonApiErrorsAware|JsonApiRenderable $e) use ($handler) {
            if ($e instanceof Throwable && $previous = $e->getPrevious()) {
                $handler->report($previous);
            }

            return false;
        });

        $this->loadTranslationsFrom(self::PATH_LANG, self::NAME);

        $this->publishes([self::PATH_LANG => $this->app->langPath('vendor/json-api-error')], self::NAME);

        TestResponse::mixin(new TestResponseMixin());
    }
}
