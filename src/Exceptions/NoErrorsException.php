<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Exceptions;

use RuntimeException;

/**
 * The exception thrown when no JSON:API errors are provided.
 */
final class NoErrorsException extends RuntimeException
{
    /**
     * Instantiate the class.
     */
    public function __construct()
    {
        parent::__construct('No JSON:API errors were provided.');
    }
}
