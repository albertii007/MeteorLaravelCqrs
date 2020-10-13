<?php

namespace MeteorCqrs\Meteor;

interface IMeteor
{
    public function Send($classpath, $data = null);
}
