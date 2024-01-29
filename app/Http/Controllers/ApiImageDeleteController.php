<?php

namespace App\Http\Controllers;

use App\Models\Image;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;

class ApiImageDeleteController extends Controller
{
    private AbstractAdapter $adapter;

    public function __construct()
    {
        $this->adapter = new Local(
            base_path('public/images')
        );
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(int $imageId)
    {
        $image = Image::findOrFail($imageId);
        $adapter = new Filesystem($this->adapter);

        if ($adapter->has($image->path)) {
            $adapter->delete($image->path);
        }

        $image->delete();

        return response()->json([
            'msg' => 'Deleted',
        ]);
    }
}
