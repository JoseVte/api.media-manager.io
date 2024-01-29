<?php

namespace App\Http\Controllers;

use App\Enums\ImageCategory;
use App\Models\Image;
use Illuminate\Http\Request;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

class ApiImageUploadController extends Controller
{
    private AbstractAdapter $adapter;

    public function __construct()
    {
        $this->adapter = new Local(
            base_path('public/images')
        );
    }

    public function __invoke(Request $request)
    {
        $validated = $this->validate($request, [
            'category' => ['required', 'string', ImageCategory::rule()],
            'file' => ['required', 'image'],
        ]);

        $adapter = new Filesystem($this->adapter);

        $file = $request->file('file') ?: $request->get('file');
        $extension = $file->clientExtension();

        $fileCount = 1;
        if ($adapter->has($validated['category'])) {
            $fileCount += count($adapter->listContents($validated['category']));
        }

        while ($adapter->has($validated['category'].'/'.$fileCount.'.'.$extension)) {
            $fileCount++;
        }

        $path = $validated['category'].'/'.$fileCount.'.'.$extension;
        $adapter->put($path, $file->getContent());

        return response()->json(Image::create([
            'category' => $validated['category'],
            'name' => $fileCount.'.'.$extension,
            'original_name' => $file->getClientOriginalName(),
            'mimetype' => $file->getMimeType(),
            'path' => $path,
        ]), Response::HTTP_CREATED);
    }
}
