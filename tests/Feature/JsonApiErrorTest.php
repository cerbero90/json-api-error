<?php

use Cerbero\JsonApiError\Data\JsonApiErrorData;
use Cerbero\JsonApiError\JsonApiError;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Contracts\Filesystem\LockTimeoutException;
use Illuminate\Support\Arr;
use Illuminate\Support\Exceptions\MathException;

it('handles JSON:API errors aware exceptions')
    ->getJson('errors-aware')
    ->assertJsonApiError('ciao');

it('handles validation exceptions')
    ->getJson('validation')
    ->assertJsonApiValidation([
        'The foo.0 field must be a string.',
        'The foo.1 field must be a string.',
        'The foo.2 field must be a string.',
    ])
    ->assertJsonApiValidation([
        'foo.0' => 'The foo.0 field must be a string.',
        'foo.1' => 'The foo.1 field must be a string.',
        'foo.2' => 'The foo.2 field must be a string.',
    ]);

it('handles only requests expecting JSON')
    ->get('validation')
    ->assertRedirect()
    ->assertSessionHasErrors();

it('handles HTTP exceptions')
    ->getJson('http-exception')
    ->assertJsonApiErrorStatus(413);

it('handles the Laravel authorization exception')
    ->getJson('authorization')
    ->assertJsonApiErrorStatus(403);

it('handles the Laravel authentication exception')
    ->getJson('authentication')
    ->assertJsonApiErrorStatus(401);

it('handles the Laravel model not found exception')
    ->getJson('not-found')
    ->assertJsonApiErrorStatus(404);

it('handles the Laravel unauthorized exception')
    ->getJson('unauthorized')
    ->assertJsonApiErrorStatus(403);

it('defines custom handlers', function () {
    JsonApiError::handle(fn (BroadcastException|MathException $e) => new JsonApiErrorData(
        'detail', 499, 'code', 'title', 'id', 'source', ['meta' => true],
    ));

    $expected = [
        'errors' => [
            [
                'id' => 'id',
                'status' => '499',
                'code' => 'code',
                'title' => 'title',
                'detail' => 'detail',
                'source' => ['pointer' => '/source'],
                'meta' => ['meta' => true],
            ],
        ],
    ];

    $this
        ->getJson('broadcast')
        ->assertExactJson($expected);

    $this
        ->getJson('math')
        ->assertExactJson($expected);

    resetState(fn () => Arr::forget(self::$handlersMap, [BroadcastException::class, MathException::class]));
});

it('maps a throwable to an HTTP status', function () {
    JsonApiError::mapToStatus(LockTimeoutException::class, 423);

    $this
        ->getJson('status')
        ->assertJsonApiErrorStatus(423);

    resetState(fn () => Arr::forget(self::$statusesMap, [LockTimeoutException::class]));
});

it('merges custom data', function () {
    $i = 0;

    JsonApiError::merge(function() use (&$i) {
        return new JsonApiErrorData(
            'detail', 488, 'code', 'title', 'id' . ++$i, 'source', ['meta' => true],
        );
    });

    $this
        ->getJson('validation')
        ->assertExactJson([
            'errors' => [
                [
                    'id' => 'id1',
                    'status' => '422',
                    'code' => 'code',
                    'title' => 'Unprocessable Content',
                    'detail' => 'The foo.0 field must be a string.',
                    'source' => ['pointer' => '/foo/0'],
                    'meta' => ['meta' => true],
                ],
                [
                    'id' => 'id2',
                    'status' => '422',
                    'code' => 'code',
                    'title' => 'Unprocessable Content',
                    'detail' => 'The foo.1 field must be a string.',
                    'source' => ['pointer' => '/foo/1'],
                    'meta' => ['meta' => true],
                ],
                [
                    'id' => 'id3',
                    'status' => '422',
                    'code' => 'code',
                    'title' => 'Unprocessable Content',
                    'detail' => 'The foo.2 field must be a string.',
                    'source' => ['pointer' => '/foo/2'],
                    'meta' => ['meta' => true],
                ],
            ],
        ]);

    resetState(fn () => self::$merge = null);
});

it('shows debugging information', function () {
    config(['app.debug' => true]);

    $this
        ->getJson('previous')
        ->assertJsonApiErrorStatus(400)
        ->assertJsonPath('errors.0.meta.message', 'current')
        ->assertJsonPath('errors.0.meta.previous_message', 'previous');

    config(['app.debug' => false]);
});
