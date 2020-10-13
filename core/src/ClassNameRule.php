<?php

namespace MeteorCqrs\Meteor\src;

use MeteorCqrs\Meteor\core\exception\MeteorInvalidClassNameException;

trait ClassNameRule
{
    private function ClassName($parsedPath)
    {
        if (strpos($parsedPath, 'Handler')) {
            $validator = substr($parsedPath, 0, strpos($parsedPath, 'Handler')) . 'Validator';
            return $validator;
        } else {
            throw new MeteorInvalidClassNameException();
        }
    }
}
