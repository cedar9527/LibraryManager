<?php
namespace api;

/**
 * An HTTP Request
 */
class Request {
    private $_verb;
    private $_args = array();
    private $_resource = '';
    
    /**
     * Initializes a Request
     * @param string $resource
     * @param string $verb
     * @param array $args
     */
    public function __construct($resource, $verb, array $args) {
        $this->_resource = $resource;
        $this->_verb = $verb;
        $this->_args = $args;
    }
    
    /**
     * Gets the requested resource
     * @return string the name of the resource
     */
    public function getResource() {
        return $this->_resource;
    }
    
    /**
     * Gets the HTTP Verb
     * @return string
     */
    public function getVerb() {
        return $this->_verb;
    }
    
    /**
     * Gets the request arguments
     * @return array
     */
    public function getArgs() {
        return $this->_args;
    }
}
