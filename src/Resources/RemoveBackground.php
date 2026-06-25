<?php

declare(strict_types=1);

namespace RunApi\Recraft\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Recraft\Models\CompletedImageTaskResponse;
use RunApi\Recraft\Models\ImageTaskResponse;
use RunApi\Recraft\Types;

/**
 * Isolates the foreground subject and removes the background, producing a transparent PNG. Uses the recraft-remove-background model.
 */
readonly class RemoveBackground extends TypedConfiguredResource
{
    /**
     * Submits a remove-background task and returns immediately with a task id.
     *
     * @param array{
     *   model: string,
     *   source_image_url: string,
     *   callback_url?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Fetches the current status of a remove-background task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits a remove-background task and polls until it completes.
     *
     * @param array{
     *   model: string,
     *   source_image_url: string,
     *   callback_url?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedImageTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedImageTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/recraft/remove_background',
            'recraft/remove-background',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::REMOVE_BACKGROUND_MODELS,
            'remove-background',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
