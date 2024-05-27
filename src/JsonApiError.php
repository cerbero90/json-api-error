<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError;

use Cerbero\JsonApiError\Contracts\JsonApiErrorsAware;
use Cerbero\JsonApiError\Contracts\JsonApiSafe;
use Cerbero\JsonApiError\Data\JsonApiErrorData;
use Cerbero\JsonApiError\Exceptions\InvalidHandlerException;
use Cerbero\JsonApiError\Exceptions\JsonApiException;
use Cerbero\JsonApiError\Exceptions\NoErrorsException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Reflector;
use Illuminate\Validation\ValidationException;
use ReflectionFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * The entry-point to render JSON:API compliant errors.
 */
final class JsonApiError
{
    /**
     * The map between throwables and handlers.
     *
     * @var array<class-string, callable>
     */
    private static array $handlersMap = [
        \Cerbero\JsonApiError\Contracts\JsonApiErrorsAware::class => [self::class, 'fromJsonApiErrorsAware'],
        \Cerbero\JsonApiError\Contracts\JsonApiSafe::class => [self::class, 'fromJsonApiSafe'],
        \Illuminate\Validation\ValidationException::class => [self::class, 'fromValidation'],
        \Symfony\Component\HttpKernel\Exception\HttpException::class => [self::class, 'fromHttpException'],
    ];

    /**
     * The map between throwables and HTTP statuses.
     *
     * @var array<class-string, int>
     */
    private static array $statusesMap = [
        \Illuminate\Auth\Access\AuthorizationException::class => 403,
        \Illuminate\Auth\AuthenticationException::class => 401,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => 404,
        \Illuminate\Validation\UnauthorizedException::class => 403,
    ];

    /**
     * The user-defined data to merge with all JSON:API errors.
     *
     * @var ?Closure(): JsonApiErrorData
     */
    private static ?Closure $merge = null;

    /**
     * The JSON:API errors.
     *
     * @var JsonApiErrorData[] $errors
     */
    public readonly array $errors;

    /**
     * Instantiate the class.
     */
    public function __construct(JsonApiErrorData ...$errors)
    {
        $this->errors = match (true) {
            empty($errors) => throw new NoErrorsException(),
            is_null($merge = self::$merge) => $errors,
            default => array_map(fn(JsonApiErrorData $error) => $error->merge($merge()), $errors),
        };
    }

    /**
     * Define a custom handler to turn the given throwable into a JSON:API error.
     *
     * @template T of Throwable
     * @param Closure(T): (JsonApiErrorData|JsonApiErrorData[]) $handler
     */
    public static function handle(Closure $handler): void
    {
        $parameters = (new ReflectionFunction($handler))->getParameters();
        /** @var class-string[] */
        $types = empty($parameters) ? [null] : (Reflector::getParameterClassNames($parameters[0]) ?: [null]);

        foreach ($types as $type) {
            throw_unless(is_subclass_of($type, Throwable::class), InvalidHandlerException::class);

            self::$handlersMap[$type] = fn(Throwable $e) => new self(...Arr::wrap($handler($e)));
        }
    }

    /**
     * Map the given throwable to the provided HTTP status.
     *
     * @param class-string $throwable
     */
    public static function mapToStatus(string $throwable, int $status): void
    {
        self::$statusesMap[$throwable] = $status;
    }

    /**
     * Define custom data to merge with all JSON:API errors.
     *
     * @param Closure(): JsonApiErrorData $callback
     */
    public static function merge(Closure $callback): void
    {
        self::$merge = $callback;
    }

    /**
     * Instantiate the class from the given raw error.
     */
    public static function raw(?string $detail = null, int $status = Response::HTTP_BAD_REQUEST): self
    {
        return new self(new JsonApiErrorData($detail, $status));
    }

    /**
     * Instantiate the class from the given throwable.
     */
    public static function from(Throwable $e): self
    {
        /** @var ?callable */
        $handler = Arr::first(self::$handlersMap, fn(callable $h, string $class) => is_a($e, $class));

        return match (true) {
            $handler !== null => $handler($e),
            isset(self::$statusesMap[$e::class]) => self::fromStatus(self::$statusesMap[$e::class]),
            default => self::unexpected($e),
        };
    }

    /**
     * Instantiate the class from the given HTTP status.
     */
    public static function fromStatus(int $status): self
    {
        return new self(new JsonApiErrorData(status: $status));
    }

    /**
     * Instantiate the class from the given unexpected throwable.
     */
    public static function unexpected(Throwable $e): self
    {
        return new self(JsonApiErrorData::unexpected($e));
    }

    /**
     * Instantiate the class from the given instance aware of JSON:API errors.
     */
    public static function fromJsonApiErrorsAware(JsonApiErrorsAware $instance): self
    {
        return new self(...$instance->jsonApiErrors());
    }

    /**
     * Instantiate the class from the given JSON:API renderable.
     */
    public static function fromJsonApiSafe(JsonApiSafe $e): self
    {
        return new self(new JsonApiErrorData($e->getMessage()));
    }

    /**
     * Instantiate the class from the given validation exception.
     */
    public static function fromValidation(ValidationException $e): self
    {
        $errors = [];

        foreach ($e->errors() as $dot => $details) {
            foreach ($details as $detail) {
                $errors[] = JsonApiErrorData::unprocessable($detail, $dot);
            }
        }

        return new self(...$errors);
    }

    /**
     * Instantiate the class from the given HTTP exception.
     */
    public static function fromHttpException(HttpExceptionInterface $e): self
    {
        return self::fromStatus($e->getStatusCode());
    }

    /**
     * Retrieve the HTTP response containing the JSON:API errors.
     */
    public function response(): JsonResponse
    {
        return new JsonResponse($this->toArray(), $this->errors[0]->status);
    }

    /**
     * Retrieve the formatted JSON:API errors.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->errors as $error) {
            $data['errors'][] = array_filter($error->toArray(), filled(...));
        }

        return $data;
    }

    /**
     * Stop the code flow to render the JSON:API errors.
     */
    public function throw(): never
    {
        throw new JsonApiException(...$this->errors);
    }
}
