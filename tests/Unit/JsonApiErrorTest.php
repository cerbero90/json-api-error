<?php

use Cerbero\JsonApiError\Exceptions\InvalidHandlerException;
use Cerbero\JsonApiError\Exceptions\NoErrorsException;
use Cerbero\JsonApiError\JsonApiError;

it('throws an exception if no JSON:API errors are provided', function () {
    new JsonApiError();
})->throws(NoErrorsException::class);

it('throws an exception if an handler is invalid', function () {
    JsonApiError::handle(fn () => true);
})->throws(InvalidHandlerException::class);
