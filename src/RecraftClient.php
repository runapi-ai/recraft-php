<?php

declare(strict_types=1);

namespace RunApi\Recraft;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Recraft\Resources\RemoveBackground;
use RunApi\Recraft\Resources\UpscaleImage;

/**
 * Provides image upscaling and background removal powered by Recraft.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class RecraftClient extends BaseClient
{
    /**
     * Upscale image operations.
     */
    public readonly UpscaleImage $upscaleImage;
    /**
     * Remove background operations.
     */
    public readonly RemoveBackground $removeBackground;

    /**
     * Create a Recraft client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->upscaleImage = UpscaleImage::fromHttp($this->http);
        $this->removeBackground = RemoveBackground::fromHttp($this->http);
    }
}
