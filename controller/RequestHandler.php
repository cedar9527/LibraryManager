<?php
namespace controller;
use api\RequestParser;
use OutOfRangeException;

/**
 * Class to handle Requests with registered controllers.
 * Note: this class implements the Command Pattern.
 **/
class RequestHandler {
    private $_parser = null;
    private $_controllers = array();
    
    /**
     * Registers a controller to handle requests
     * Note: this should be called before parse.
     * @param string $key they key to seek the registered controller
     * @param IController $controller the controller to register
     **/
    function register($key, IController $controller) {
        array_push($this->_controllers, $key, $controller);
    }
    
    /**
     * Parses a request
     * @param RequestParser $requestParser the parsed request to handle
     **/
    function handle(RequestParser $requestParser) {
        $this->_parser = $requestParser;
        $controller = $this->_parser->getController();
        
        if(array_key_exists($controller, $this->_controllers)) {
            $this->_controllers[$controller]->execute();
        } else {
            throw new OutOfRangeException("Le controlleur {$controller} n'existe pas.");
        }
    }
}
?>