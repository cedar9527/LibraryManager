<?php

namespace controller;

use api\Request;
use OutOfRangeException;

/**
 * Class to handle Requests with registered controllers.
 * Note: this class implements the Command Pattern.
 * */
class Dispatcher {

    private $_parser = null;
    private $_controllers = array();

    /**
     * Registers a controller to handle requests
     * Note: this should be called before parse.
     * @param string $key they key to seek the registered controller
     * @param IController $controller the controller to register
     * */
    function register($key, IController $controller) {
        array_push($this->_controllers, $key, $controller);
    }

    /**
     * Parses a request
     * @param Request $request the parsed request to handle
     * */
    function handle(Request $request) {
        $resource = $request->getResource();
        if (array_key_exists($resource, $this->_controllers)) {
            $this->_controllers[$resource]->execute();
        } else {
            throw new OutOfRangeException("Le controlleur pour la resource {$resource} n'existe pas.");
        }
    }

}
