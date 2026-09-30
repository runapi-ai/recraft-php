# Recraft PHP SDK for RunAPI

[![Packagist](https://img.shields.io/packagist/v/runapi-ai/recraft)](https://packagist.org/packages/runapi-ai/recraft)
[![License](https://img.shields.io/github/license/runapi-ai/recraft-php)](https://github.com/runapi-ai/recraft-php/blob/main/LICENSE)

The Recraft PHP SDK is the language-specific package for Recraft
on RunAPI. Use this package when your application needs Composer installs,
associative-array request bodies, task status lookup, and consistent RunAPI
errors in PHP.

This README is the PHP package guide for the public `recraft-php` split
repository. For model details, use https://runapi.ai/models/recraft; for API
reference, use https://runapi.ai/docs/api/recraft/remove-background; for SDK docs, use
https://runapi.ai/docs/resources/sdks.

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

$removeBackgroundTask = $client->removeBackground->create([
    'model' => 'recraft-remove-background',
    'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
]);

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

Use `create()` to submit a task and return quickly, `get()` to fetch the latest
task state, and `run()` when a script should create and poll until completion.
In web request handlers, prefer `create()` plus webhook or later `get()`
polling so a worker is not held open.


RunAPI-generated file URLs are temporary. Download and store generated files
in your own durable storage within the retention window; do not treat returned
URLs as long-term assets.

## Language notes

Pass request parameters as associative arrays with snake_case keys. The
available resources are `upscaleImage`, `removeBackground`. Keep `RUNAPI_API_KEY` in the environment
or your secret manager; never commit API keys or callback secrets.

## Links

- Model page: https://runapi.ai/models/recraft
- SDK docs: https://runapi.ai/docs/resources/sdks
- Product docs: https://runapi.ai/docs/api/recraft/remove-background
- Pricing and rate limits: https://runapi.ai/models/recraft/crisp-upscale
- Full catalog: https://runapi.ai/models
- GitHub repository: https://github.com/runapi-ai/recraft-php
- Multi-language SDK repository: https://github.com/runapi-ai/recraft-sdk

## License

Licensed under the Apache License, Version 2.0.
