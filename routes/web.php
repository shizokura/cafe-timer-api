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

$router->get('/', 'FrontendController@index');
$router->get('/codes', 'FrontendController@codes');
$router->get('/generate_codes', 'FrontendController@generate_codes');
$router->post('/api/user_info', 'ApiController@user_info');
$router->post('/api/update_time', 'ApiController@update_time');
$router->post('/api/topup', 'ApiController@topup');
$router->post('/api/register', 'ApiController@register');
$router->post('/api/claim_points', 'ApiController@claim_points');

$router->get('/viewer_online', 'ApiController@viewer_online');
$router->get('/check_unused_code', 'ApiController@check_unused_code');
$router->get('/view_members_code', 'ApiController@view_members_code');
$router->get('/view_member_points', 'ApiController@view_member_points');
$router->get('/view_duplicate_code', 'ApiController@view_duplicate_code');
