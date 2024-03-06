<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Feature;

use Cerbero\JsonApiError\JsonApiError;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\Filesystem\LockTimeoutException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Support\Exceptions\MathException;
use Illuminate\Validation\UnauthorizedException;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use WithWorkbench;

    /**
     * @param \Illuminate\Routing\Router $router
     */
    protected function defineRoutes($router)
    {
        $router->get('errors-aware', fn () => JsonApiError::raw('ciao')->throw());
        $router->get('validation', fn () => validator(['foo' => [1,2,3]], ['foo.*' => 'string'])->validate());
        $router->get('http-exception', fn () => throw new PostTooLargeException());
        $router->get('authorization', fn () => throw new AuthorizationException());
        $router->get('authentication', fn () => throw new AuthenticationException());
        $router->get('not-found', fn () => throw new ModelNotFoundException());
        $router->get('unauthorized', fn () => throw new UnauthorizedException());
        $router->get('broadcast', fn () => throw new BroadcastException());
        $router->get('math', fn () => throw new MathException());
        $router->get('status', fn () => throw new LockTimeoutException());
        $router->get('previous', fn () => throw new Exception('current', 0, new Exception('previous')));
    }
}
