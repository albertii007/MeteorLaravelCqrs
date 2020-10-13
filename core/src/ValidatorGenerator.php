<?php

namespace MeteorCqrs\Meteor\src;

use Illuminate\Foundation\Validation\ValidatesRequests;
use ReflectionClass;

trait ValidatorGenerator
{
    use ClassNameRule, CreateValidatorFile, ValidatesRequests;

    private function Validator($classpath, $data = null)
    {        
        $reflectClass = new ReflectionClass($classpath);

        $filepath = $reflectClass->getFileName();
        
        $validator = $this->ClassName($reflectClass->getName());

        if (class_exists($validator)) {

            $class = new $validator;

            return $this->validate($data,$class->Validations(),$class->Messages());

        } else {

            if (app()->environment('local')) {
                return $this->CreateValidatorFile($validator, $reflectClass->getName(), $filepath);
            } else {
                return "You can create files only on Local";
            }

        }
    }
}
