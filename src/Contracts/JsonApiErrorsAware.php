<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Contracts;

use Cerbero\JsonApiError\Data\JsonApiErrorData;

/**
 * The interface to provide JSON:API errors
 */
interface JsonApiErrorsAware
{
    /**
     * Retrieve all the occurred JSON:API errors
     *
     * @return JsonApiErrorData[]
     */
    public function jsonApiErrors(): array;
}
