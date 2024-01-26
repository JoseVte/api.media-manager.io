<?php

namespace App\Http\Controllers;

use App\Models\Image;

class ApiImageCategoryController extends Controller
{
    public function __invoke(string $category)
    {
        return response()->json(Image::where('category', $category)->get());
    }
}
