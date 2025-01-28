<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Providers;

use Cerbero\JsonApiError\Contracts\JsonApiErrorsAware;
use Cerbero\JsonApiError\Contracts\JsonApiSafe;
use Cerbero\JsonApiError\Exceptions\JsonApiException;
use Cerbero\JsonApiError\JsonApiError;
use Cerbero\JsonApiError\Services\TestResponseMixin;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel;
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
     * @param \Illuminate\Foundation\Http\Kernel $kernel
     */
    public function boot(ExceptionHandler $handler, Kernel $kernel): void
    {
        $handler->renderable(function (Throwable $e, Request $request): ?JsonApiError {
            return JsonApiError::handlesRequest($request) ? JsonApiError::from($e) : null;
        });

        $handler->reportable(function (JsonApiException|JsonApiErrorsAware|JsonApiSafe $e) use ($handler): bool {
            if ($e instanceof Throwable && $previous = $e->getPrevious()) {
                $handler->report($previous);
            }

            return false;
        });

        $kernel->prependMiddlewareToGroup('api', JsonApiError::$middleware);

        $this->loadTranslationsFrom(self::PATH_LANG, self::NAME);

        $this->publishes([self::PATH_LANG => $this->app->langPath('vendor/json-api-error')], self::NAME);

        TestResponse::mixin(new TestResponseMixin());
    }
}
