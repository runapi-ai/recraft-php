# Recraft PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/recraft)](https://packagist.org/packages/runapi-ai/recraft)
[![License](https://img.shields.io/github/license/runapi-ai/recraft-php)](https://github.com/runapi-ai/recraft-php/blob/main/LICENSE)

The Recraft PHP SDK is the Composer package for Recraft on RunAPI. Use it when your PHP application needs associative-array request bodies, task status lookup, polling helpers, file helpers, and consistent RunAPI errors.

## Install

```bash
composer require runapi-ai/recraft
```

## Quick start

```php
<?php

require __DIR__ . "/vendor/autoload.php";

use RunApi\Recraft\RecraftClient;

$client = new RecraftClient(); // reads RUNAPI_API_KEY

$task = $client->upscaleImage->create([
    'model' => 'recraft-crisp-upscale',
    'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
]);

$status = $client->upscaleImage->get($task->id);

$result = $client->upscaleImage->run([
    'model' => 'recraft-crisp-upscale',
    'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
]);

echo $result->images[0]->url . PHP_EOL;
```

Use `create()` to submit a task and return quickly, `get()` to fetch the latest task state, and `run()` when a script should create and poll until completion. In web request handlers, prefer `create()` plus webhook or later `get()` polling so a worker is not held open.

Returned file URLs are temporary. Download and store generated files in your own durable storage within the retention window.

All SDK exceptions inherit from `RunApi\Core\Errors\RunApiException`, including validation, authentication, rate limit, task failure, and task timeout errors.

## Links

- Model page: https://runapi.ai/models/recraft
- SDK docs: https://runapi.ai/docs#sdk-recraft
- Product docs: https://runapi.ai/docs#recraft
- Pricing and rate limits: https://runapi.ai/models/recraft/crisp-upscale
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/recraft-php
- Multi-language SDK repository: https://github.com/runapi-ai/recraft-sdk

## License

Licensed under the Apache License, Version 2.0.
