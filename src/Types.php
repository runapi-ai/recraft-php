<?php

declare(strict_types=1);

namespace RunApi\Recraft;

/**
 * Constants for model slugs supported by the Recraft PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const UPSCALE_IMAGE_MODELS = ['recraft-crisp-upscale'];

    /** @var list<string> */
    public const REMOVE_BACKGROUND_MODELS = ['recraft-remove-background'];

    private function __construct()
    {
    }
}
