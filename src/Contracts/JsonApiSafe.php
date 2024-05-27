<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Contracts;

use Throwable;

/**
 * The interface to mark a Throwable message safe to be displayed in the JSON:API error responses.
 */
interface JsonApiSafe extends Throwable
{
    //
}
