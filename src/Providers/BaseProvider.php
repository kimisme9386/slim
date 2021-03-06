<?php

namespace LaravelBridge\Slim\Providers;

use Illuminate\Support\ServiceProvider;
use Slim\CallableResolver;
use Slim\Http\Environment;
use Slim\Router;

class BaseProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerCallableResolver();
        $this->registerEnvironment();
        $this->registerRouter();
    }

    protected function registerCallableResolver()
    {
        $this->app->bindIf('callableResolver', function () {
            return new CallableResolver($this->app);
        }, true);
    }

    protected function registerEnvironment()
    {
        $this->app->bindIf('environment', function () {
            return new Environment($_SERVER);
        }, true);
    }

    protected function registerRouter()
    {
        $this->app->bindIf('router', function () {
            $routerCacheFile = false;
            if (isset($this->app->make('settings')['routerCacheFile'])) {
                $routerCacheFile = $this->app->make('settings')['routerCacheFile'];
            }
            $router = (new Router())->setCacheFile($routerCacheFile);
            if (method_exists($router, 'setContainer')) {
                $router->setContainer($this->app);
            }
            return $router;
        }, true);
    }
}
