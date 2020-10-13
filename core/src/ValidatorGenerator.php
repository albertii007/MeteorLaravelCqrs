<?php

namespace MeteorCqrs\Meteor\src;

use ReflectionClass;
use Illuminate\Support\Facades\Validator;
use MeteorCqrs\Meteor\exception\MeteorValidatorException;

trait ValidatorGenerator
{
    use ClassNameRule, CreateValidatorFile;

    private function Validator($classpath, $data = null)
    {        
        $reflectClass = new ReflectionClass($classpath);

        $filepath = $reflectClass->getFileName();
        
        $validator = $this->ClassName($reflectClass->getName());

        if (class_exists($validator)) {
            $class = new $validator;

            $validator = Validator::make($data->all(),$class->Validations(),$class->Messages());
            
            if($validator->fails()){
                throw new MeteorValidatorException($validator->errors());
            }else{
                return $class->Valdations();
            }

        } else {

            if (app()->environment('local')) {
                return $this->CreateValidatorFile($validator, $reflectClass->getName(), $filepath);
            } else {
                return "You can create files only on Local";
            }

        }
    }
}
