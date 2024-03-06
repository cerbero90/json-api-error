<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Exceptions;

use RuntimeException;

/**
 * The exception thrown when defining an invalid handler.
 */
final class InvalidHandlerException extends RuntimeException
{
    /**
     * Instantiate the class.
     */
    public function __construct()
    {
        parent::__construct('The handler parameter must be an instance of Throwable.');
    }
}
