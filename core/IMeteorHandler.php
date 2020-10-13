<?php

namespace MeteorCqrs\Meteor;

use Illuminate\Http\Request;

interface IMeteorHandler
{
    public function Handler(Request $request);
}
