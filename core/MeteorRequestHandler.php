<?php

namespace MeteorCqrs\Meteor;

use Illuminate\Http\Request;

class MeteorRequestHandler
{
    private $_IMeteorHandler;

    public function __construct(IMeteorHandler $IMeteorHandler)
    {
        $this->_IMeteorHandler = $IMeteorHandler;
    }

    /**
     * Handle
     *
     * @param array $validatedData data from validator
     * @param mixed Request
     * @return Handler of class
     */
    public function Handle(array $validatedData, Request $request = null)
    {
        if ($request != null) {
            foreach ($request->all() as $key => $arrayRequest) {
                if (!in_array($key, array_keys($validatedData))) {
                    $request->request->remove($key);
                }
            }
            $request->request->remove('password_confirmation');
        } else {
            $request = new Request([]);
        }
        return $this->_IMeteorHandler->Handler($request);
    }
}
