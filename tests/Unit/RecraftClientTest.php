<?php

declare(strict_types=1);

namespace RunApi\Recraft\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Recraft\Models\CompletedImageTaskResponse;
use RunApi\Recraft\RecraftClient;
use RunApi\Recraft\Resources\RemoveBackground;
use RunApi\Recraft\Resources\UpscaleImage;

final class RecraftClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new RecraftClient(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(UpscaleImage::class, $client->upscaleImage);
        self::assertInstanceOf(RemoveBackground::class, $client->removeBackground);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new RecraftClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->upscaleImage->create([
            'model' => 'recraft-crisp-upscale',
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/recraft/upscale_image', $transport->requests[0]->getUri()->getPath());
        self::assertSame('recraft-crisp-upscale', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","images":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new RecraftClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->upscaleImage->run([
            'model' => 'recraft-crisp-upscale',
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        ]);

        self::assertInstanceOf(CompletedImageTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->images[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/recraft/upscale_image/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new RecraftClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('images is required');

        $client->upscaleImage->run([
            'model' => 'recraft-crisp-upscale',
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        ]);
    }


    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new RecraftClient(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->removeBackground->create([
            'model' => 'recraft-remove-background',
            'source_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        ]);

        self::assertSame('/api/v1/recraft/remove_background', $transport->requests[0]->getUri()->getPath());
    }
}
