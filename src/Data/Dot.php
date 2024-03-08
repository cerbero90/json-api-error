<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Data;

/**
 * The dot-notation value object.
 */
final class Dot
{
    /**
     * Instantiate the class.
     */
    public function __construct(public readonly string $dot) {}

    /**
     * Retrieve the dot converted into a JSON pointer.
     */
    public function toJsonPointer(): string
    {
        $search = ['~', '/', '.', '*', '\\', '"'];
        $replace = ['~0', '~1', '/', '-', '\\\\', '\"'];

        return $this->dot == '*' ? '' : '/' . str_replace($search, $replace, $this->dot);
    }
}
