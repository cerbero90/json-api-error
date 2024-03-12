<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Contracts;

use Throwable;

/**
 * The interface to let a Throwable display its message in the JSON:API error responses.
 */
interface JsonApiRenderable extends Throwable
{
    //
}
