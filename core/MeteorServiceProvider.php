<?php

namespace MeteorCqrs\Meteor;

use Illuminate\Support\ServiceProvider;
use MeteorCqrs\Meteor\IMeteorHandler;
use ReflectionClass;
use ReflectionParameter;

class MeteorServiceProvider extends ServiceProvider
{
    public function boot():void
    {
        $this->app->bind(IMeteor::class, Meteor::class);

        $this->registerService();
    }

    public function registerService():void
    {

        $routeCollection = $this->app->router->getRoutes();

        $currentRoute = $routeCollection[$this->getPath()];

        $classAndFunction = $this->getController($currentRoute['action']);

        if($classAndFunction == 'None') return;

        $this->BindInstance($classAndFunction);

    }

    private function BindInstance(array $classAndFunction):void
    {
        $baseClass = new ReflectionClass($classAndFunction[0]);

        $baseClassParameters = $baseClass->getMethod($classAndFunction[1])->getParameters();

        if(count($baseClassParameters) == 0) {
            return;
        }

        for ($i=0; $i < count($baseClassParameters); $i++) { 
            $class = new ReflectionParameter(array($classAndFunction[0], $classAndFunction[1]),$i);
            if($class->getClass()->implementsInterface(IMeteorHandler::class)) break;
        }

        $this->app->bind(IMeteorHandler::class, $class->getClass()->name);
    }

    private function getPath(): string
    {
        $path = preg_replace('#/+#','/',$_SERVER['REQUEST_URI']);

        $parsedPath = parse_url($path);

        return (string)$_SERVER['REQUEST_METHOD'].$parsedPath['path'];
    }

    protected function getController(array $action)
    {
        if (empty($action['uses'])) {
            return 'None';
        }
        else if(!empty($action['as'])){
            if(!strpos($action['as'], 'swagger')){
                return 'None';
            }
        }
        return explode("@", $action['uses']);
    }
}