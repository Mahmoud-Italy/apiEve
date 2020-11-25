<?php
use App\Legacy\Routes;
$app = new Routes($router);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

# API v1 created at November, 2020
$router->group(['prefix' => 'v1'], function($router) use ($app) {

    /** Backend **/
    $router->group(['prefix' => 'dashboard'], function($router) use ($app) {
        $app->authResource('auth', 'AuthController');
        $router->group(['middleware' => ['auth', 'belongsTo']], function($router) use ($app) {
            $app->apiResource('events', 'EventController');
        });
    });

    /** Frontend **/

});


