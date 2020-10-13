<?php

namespace MeteorCqrs\Meteor\exception;

use Exception;

class MeteorInvalidClassNameException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response("This class must end with 'Handler' keyword", 500);
    }
}
