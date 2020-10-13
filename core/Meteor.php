<?php

namespace MeteorCqrs\Meteor;

use MeteorCqrs\Meteor\src\ValidatorGenerator;

class Meteor implements IMeteor
{
    use ValidatorGenerator;

    private $_meteorRequestHandler;

    public function __construct(MeteorRequestHandler $meteorRequestHandler)
    {
        $this->_meteorRequestHandler = $meteorRequestHandler;
    }

    public function Send($classpath, $data = null)
    {
        if ($data) {
            $validatorData = $this->Validator($classpath, $data);
        } else {
            $validatorData = [];
        }

        return $this->_meteorRequestHandler->Handle($validatorData, $data);
    }
}
