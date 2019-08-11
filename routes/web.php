<?php

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
    return $router->app->version();
});

$router->post('/api/user_info', 'ApiController@user_info');
$router->post('/api/update_time', 'ApiController@update_time');
$router->post('/api/topup', 'ApiController@topup');
$router->post('/api/register', 'ApiController@register');
$router->post('/api/claim_points', 'ApiController@claim_points');