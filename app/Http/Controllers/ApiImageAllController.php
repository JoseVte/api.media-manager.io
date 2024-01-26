<?php

namespace App\Http\Controllers;

use App\Models\Image;

class ApiImageAllController extends Controller
{
    public function __invoke()
    {
        return response()->json(Image::all()->groupBy('category'));
    }
}
