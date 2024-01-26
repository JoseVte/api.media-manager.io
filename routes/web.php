<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('categories', 'ApiCategoryAllController');

    $router->get('images', 'ApiImageAllController');

    $router->get('images/{category}', 'ApiImageCategoryController');

    $router->post('images/upload', 'ApiImageUploadController');
});
