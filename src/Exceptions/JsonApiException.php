<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Exceptions;

use Cerbero\JsonApiError\Data\JsonApiErrorData;
use Cerbero\JsonApiError\JsonApiError;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

/**
 * The exception thrown to render the JSON:API errors.
 */
class JsonApiException extends Exception implements Responsable
{
    /**
     * @var JsonApiErrorData[] $errors
     */
    public readonly array $errors;

    /**
     * Instantiate the class.
     */
    public function __construct(JsonApiErrorData ...$errors)
    {
        $this->errors = $errors;
    }

    /**
     * Render the JSON:API errors.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function toResponse($request): Response
    {
        return (new JsonApiError(...$this->errors))->toResponse($request);
    }
}
