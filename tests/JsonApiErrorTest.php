<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError;

use Cerbero\JsonApiError\Providers\JsonApiErrorServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * The package test suite.
 */
final class JsonApiErrorTest extends TestCase
{
    /**
     * Retrieve the package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return class-string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            JsonApiErrorServiceProvider::class,
        ];
    }
}
