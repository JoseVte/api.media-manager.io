<?php

namespace App\Http\Controllers;

use App\Enums\ImageCategory;
use Illuminate\Support\Str;

class ApiCategoryAllController extends Controller
{
    public function __invoke()
    {
        return response()->json(array_map(static fn(ImageCategory $imageCategory) => [
            'name' => Str::ucfirst($imageCategory->value),
            'value' => $imageCategory->value,
        ], ImageCategory::cases()));
    }
}
