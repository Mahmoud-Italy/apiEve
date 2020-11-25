<?php
namespace App\Legacy;

class Routes
{
    protected $app;
      
    public function __construct($router='')
    {
      $this->app = $router;
    }
    
    function authResource($uri, $controller)
    {
      $this->app->post($uri.'/register', $controller.'@register');
      $this->app->post($uri.'/verification', $controller.'@verification');
      $this->app->post($uri.'/login', $controller.'@login');
      $this->app->post($uri.'/logout', $controller.'@logout');
      $this->app->post($uri.'/refresh', $controller.'@refresh');
      $this->app->post($uri.'/password/forget', $controller.'@forget');
      $this->app->post($uri.'/password/reset', $controller.'@reset');
      $this->app->get($uri.'/me', $controller.'@me');
    }

    function apiResource($uri, $controller)
    {
      $this->app->get($uri, $controller.'@index');
      $this->app->post($uri, $controller.'@store');
      $this->app->get($uri.'/me', $controller.'@me');
      $this->app->get($uri.'/{id}', $controller.'@show');
      $this->app->put($uri.'/{id}', $controller.'@update');
      $this->app->delete($uri.'/{id}', $controller.'@destroy');

      $this->app->post($uri.'/active/{id}', $controller.'@active');
      $this->app->post($uri.'/inactive/{id}', $controller.'@inactive');
      $this->app->post($uri.'/trash/{id}', $controller.'@trash');
      $this->app->post($uri.'/restore/{id}', $controller.'@restore');
      $this->app->post($uri.'/export', $controller.'@export');

      // feel free to add more
    }


    function artisanResource($uri, $controller)
    {
      $this->app->get($uri.'/cache-clear', $controller.'@cacheClear');
      $this->app->get($uri.'/config-clear', $controller.'@configClear');
      $this->app->get($uri.'/view-clear', $controller.'@viewClear');
      $this->app->get($uri.'/route-cache', $controller.'@routeCache');
      $this->app->get($uri.'/route-clear', $controller.'@routeClear');
    }

    function frontResource($uri, $controller)
    {
      $this->app->get($uri, $controller.'@index');
      $this->app->post($uri, $controller.'@store');
      $this->app->get($uri.'/{slug}', $controller.'@show');
    }

    function meResource($uri, $controller)
    {
      $this->app->get($uri, $controller.'@index');
      $this->app->get($uri.'/account', $controller.'@account');
      $this->app->post($uri, $controller.'@store');
      $this->app->post($uri.'/password', $controller.'@update');
    }

    public function legacy($uri='')
    {
        $controller = 'AppController';
        if(request()->segment(0)) {
           $controller = request()->segment(0).'Controller';
        }

        $uri = '/';
        if(request()->segment(1)) {
          $uri = request()->segment(1);
        }
        
        $this->app->get($uri, $controller.'@index');
        $this->app->post($uri, $controller.'@store');
        $this->app->get($uri.'/{id}', $controller.'@show');
        $this->app->post($uri.'/{id}', $controller.'@update');
        $this->app->put($uri.'/{id}', $controller.'@update');
        $this->app->delete($uri.'/{id}', $controller.'@destroy');
    }
}
