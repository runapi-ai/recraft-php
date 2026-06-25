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
 * Increases image resolution while preserving detail and sharpness. Uses the recraft-crisp-upscale model.
 */
readonly class UpscaleImage extends TypedConfiguredResource
{
    /**
     * Submits an upscale-image task and returns immediately with a task id.
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
     * Fetches the current status of an upscale-image task by id.
     */
    public function get(string $id, ?RequestOptions $options = null): ImageTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var ImageTaskResponse $response */
        return $response;
    }

    /**
     * Submits an upscale-image task and polls until it completes.
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
            '/api/v1/recraft/upscale_image',
            'recraft/upscale-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
            Types::UPSCALE_IMAGE_MODELS,
            'upscale-image',
            ImageTaskResponse::class,
            CompletedImageTaskResponse::class,
        );
    }
}
