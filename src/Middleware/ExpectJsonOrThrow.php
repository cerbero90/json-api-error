<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * The middleware to ensure that the request expects a JSON response.
 */
class ExpectJsonOrThrow
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->expectsJson()) {
            return $next($request);
        }

        throw $this->throwable();
    }

    /**
     * Retrieve the Throwable instance to throw.
     */
    protected function throwable(): Throwable
    {
        return new NotFoundHttpException();
    }
}
