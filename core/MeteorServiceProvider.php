<?php

namespace MeteorCqrs\Meteor;

use Closure;
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

        $routeCollection = $this->app->router->getRoutes()->get();
        $data = null;

        foreach ($routeCollection as $key => $value) {
            if($value->uri == $this->getPath()->path && $value->methods[0] == $this->getPath()->method){
                $data = $value;
                break;
            }
        }

        if($data == null) return;

        $classAndFunction = $this->getController($data->action);

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

    private function getPath(): object
    {
        $actual_link = url()->current();

        try {
            $parsedPath = parse_url($actual_link);

            $uri = $parsedPath['path'];
    
            if($uri[0] === '/'){
                $uri = substr($uri,1);
            }

            $method = $_SERVER['REQUEST_METHOD'];

        } catch (\Throwable $th) {
            $uri = '/';
            $method = '';
        }
       

        return (object)[
            "path" => $uri,
            "method"=>$method
        ];
    }

    protected function getController(array $action)
    {
        if (empty($action['uses'])) {
            return 'None';
        }
        else if($action['uses'] instanceof Closure) {
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