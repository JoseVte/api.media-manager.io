<?php

use App\Enums\ImageCategory;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

test('show all images', function () {
    $response = $this->get($this->routeApi.'/images');

    $response->assertResponseOk();
    $response->response->assertJsonStructure([
        '*' => [['id', 'name', 'category', 'original_name', 'path', 'mimetype', 'created_at', 'updated_at', 'url']],
    ]);
});

test('show selected category images', function () {
    $image = Image::factory()->create();
    $response = $this->get($this->routeApi.'/images/'.$image->category->value);

    $response->assertResponseOk();
    $response->response->assertJsonStructure([
        ['id', 'name', 'category', 'original_name', 'path', 'mimetype', 'created_at', 'updated_at', 'url'],
    ]);
    $this->assertStringContainsString($image->name, $response->response->getContent());
    $adapter = new Filesystem(new Local(
        base_path('public/images')
    ));

    if ($adapter->has($image->path)) {
        $adapter->delete($image->path);
    }
    $image->delete();
});

test('upload images', function () {
    $response = $this->post($this->routeApi.'/images/upload', [
        'category' => Arr::random(ImageCategory::cases())->value,
        'file' => UploadedFile::fake()->image('test.png'),
    ]);

    $response->assertResponseStatus(201);
    $response->response->assertJsonStructure(['id', 'name', 'category', 'original_name', 'path', 'mimetype', 'created_at', 'updated_at', 'url']);
    $image = Image::latest()->firstOrFail();
    $this->assertStringContainsString($image->name, $response->response->getContent());
    $adapter = new Filesystem(new Local(
        base_path('public/images')
    ));

    if ($adapter->has($image->path)) {
        $adapter->delete($image->path);
    }
    $image->delete();
});

test('delete image', function () {
    $image = Image::factory()->create();
    $response = $this->delete($this->routeApi.'/images/'.$image->id);

    $response->assertResponseOk();
    $response->response->assertJson([
        'msg' => 'Deleted',
    ]);
    $adapter = new Filesystem(new Local(
        base_path('public/images')
    ));

    expect($adapter->has($image->path))->toBeFalse();
});
