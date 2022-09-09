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

$router->post('/v1/movie', 'MovieController@create');

$router->get('/v1/movie', 'MovieController@read');

$router->delete('/v1/movie/{id}', 'MovieController@delete');

$router->put('/v1/movie/{id}', 'MovieController@update');
