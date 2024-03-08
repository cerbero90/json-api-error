<?php

declare(strict_types=1);

namespace Cerbero\JsonApiError\Services;

use Cerbero\JsonApiError\Data\Dot;
use Closure;
use Illuminate\Testing\Assert;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * The mixin to test JSON:API error responses.
 */
final class TestResponseMixin
{
    /**
     * Assert that the response contains the given JSON:API error.
     *
     * @return Closure(string, int): TestResponse
     */
    public function assertJsonApiError(): Closure
    {
        return function (string $detail, int $status = Response::HTTP_BAD_REQUEST) {
            /**
             * @var TestResponse $this
             */
            return $this // @phpstan-ignore-line
                ->assertJsonApiErrorStructure()
                ->assertJsonPath('errors.0.status', (string) $status)
                ->assertJsonPath('errors.0.title', __("json-api-error::statuses.{$status}.title"))
                ->assertJsonPath('errors.0.detail', $detail);
        };
    }

    /**
     * Assert that the response contains the given JSON:API validation errors.
     *
     * @return Closure(array<string|int, string>): TestResponse
     */
    public function assertJsonApiValidationErrors(): Closure
    {
        return function (array $expected) {
            /**
             * @var TestResponse $this
             */
            $this // @phpstan-ignore-line
                ->assertJsonApiErrorStructure()
                ->assertJsonPath('errors', fn(array $errors) => collect($errors)->every(function (array $error) {
                    return $error['status'] === '422' && $error['title'] === __('json-api-error::statuses.422.title');
                }));

            if (array_is_list($expected)) {
                Assert::assertEqualsCanonicalizing($expected, $this->json('errors.*.detail'));

                return $this;
            }

            $actual = $this->collect('errors')->pluck('detail', 'source.pointer')->all();

            foreach ($expected as $dot => $detail) {
                $pointer = (new Dot($dot))->toJsonPointer();
                $message = "The field [{$dot}] does not have the error [{$detail}].";

                Assert::assertSame($detail, $actual[$pointer] ?? null, $message);

                unset($actual[$pointer]);
            }

            Assert::assertEmpty($actual, 'Other unexpected validation errors occurred.');

            return $this;
        };
    }

    /**
     * Assert that the response contains the JSON:API error for the given HTTP status.
     *
     * @return Closure(int): TestResponse
     */
    public function assertJsonApiErrorStatus(): Closure
    {
        return function (int $status) {
            /**
             * @var TestResponse $this
             */
            return $this // @phpstan-ignore-line
                ->assertJsonApiErrorStructure()
                ->assertJsonPath('errors.0.status', (string) $status)
                ->assertJsonPath('errors.0.title', __("json-api-error::statuses.{$status}.title"))
                ->assertJsonPath('errors.0.detail', __("json-api-error::statuses.{$status}.detail"));
        };
    }

    /**
     * Assert that the response contains JSON:API compliant errors.
     *
     * @return Closure(): TestResponse
     */
    public function assertJsonApiErrorStructure(): Closure
    {
        return function () {
            /**
             * @var TestResponse $this
             */
            return $this->assertJsonStructure([
                'errors' => [
                    '*' => [
                        'status',
                        'title',
                        'detail',
                    ],
                ],
            ]);
        };
    }
}
