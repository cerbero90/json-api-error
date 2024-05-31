<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * The JSON:API error data.
 *
 * @implements Arrayable<string, mixed>
 */
final class JsonApiErrorData implements Arrayable
{
    /**
     * Instantiate the class.
     *
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public readonly ?string $detail = null,
        public readonly int $status = Response::HTTP_BAD_REQUEST,
        public readonly ?string $code = null,
        public readonly ?string $title = null,
        public readonly mixed $id = null,
        public readonly ?string $source = null,
        public readonly array $meta = [],
    ) {}

    /**
     * Instantiate the class from the given unexpected throwable.
     */
    public static function unexpected(Throwable $e): self
    {
        return new self(status: Response::HTTP_INTERNAL_SERVER_ERROR, meta: config('app.debug') ? [
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
            'previous_message' => $e->getPrevious()?->getMessage(),
            'previous_trace' => $e->getPrevious()?->getTrace(),
        ] : []);
    }

    /**
     * Instantiate the class from the given unprocessable entity.
     */
    public static function unprocessable(string $detail, ?string $dot = null): self
    {
        return new self($detail, Response::HTTP_UNPROCESSABLE_ENTITY, source: $dot);
    }

    /**
     * Retrieve the error as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => (string) $this->status,
            'code' => $this->code,
            'title' => $this->title ?: __("json-api-error::statuses.{$this->status}.title"),
            'detail' => $this->detail ?: __("json-api-error::statuses.{$this->status}.detail"),
            'source' => $this->source ? ['pointer' => (new Dot($this->source))->toJsonPointer()] : null,
            'meta' => $this->meta,
        ];
    }

    /**
     * Merge the given JSON:API error.
     */
    public function merge(JsonApiErrorData $data): self
    {
        $filled = array_filter((array) $this, filled(...));
        $mergeable = Arr::only((array) $data, ['id', 'code', 'meta']);

        return new self(...array_replace_recursive($mergeable, $filled));
    }
}
