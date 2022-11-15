<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return "inventario-peliculas";
});

$router->group(['prefix' => '/v1'], function ($router) {
    $router->group(['prefix' => '/movie'], function ($router) {
        $router->post('', 'MovieController@create');

        $router->get('', 'MovieController@read');

        $router->delete('/{id}', 'MovieController@delete');

        $router->put('/{id}', 'MovieController@update');
    });

    $router->group(['prefix' => '/category'], function ($router) {
        $router->post('', 'CategoryController@create');

    });
});
