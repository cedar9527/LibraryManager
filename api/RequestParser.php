<?php

namespace api;

use Exception;

/**
 * HTTP request parser.
 * */
class RequestParser {
    
    private $_request;

    private function _isSpecialVerb($verb) {
        return $verb == HttpVerbs::POST && filter_has_var(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD);
    }

    private function _checkSpecialVerb() {
        $specialMethod = filter_input(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD);
        if ( $specialMethod != HttpVerbs::PUT && $specialMethod != HttpVerbs::DELETE) {
            throw new Exception('Invalid Header', 500);
        }
    }

    /**
     * Compute the arguments
     * @param string $verb the HTTP verb
     * @return array
     * @throws Exception if the HTTP verb is invalid
     */
    private function _computeArgs($verb) {
        switch ($verb) {
            case HttpVerbs::POST:
            case HttpVerbs::DELETE:
                $args = $this->_cleanInputs(filter_input_array(INPUT_POST));
                break;
            case HttpVerbs::GET:
            case HttpVerbs::PUT:
                $args = $this->_cleanInputs(filter_input_array(INPUT_GET));
                break;
            default:
                throw new Exception('Invalid Method', 405);
        }
        return $args;
    }

    /**
     * Cleans input data by removing useless spaces and tags
     * @param array $data an associative array of input data (key => value)
     * @return array an associative array with sanitized inputs
     */
    private function _cleanInputs($data) {
        $cleanInputs = array();
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $cleanInputs[$key] = $this->_cleanInputs($value);
            }
        } elseif(!is_null($data)) {
            $cleanInputs = trim(strip_tags($data));
        }

        return $cleanInputs;
    }
    
    /**
     * Gets the verb from HTTP Server
     * @return string
     */
    private function _computeVerb() {
        $verb = filter_input(INPUT_SERVER, HttpHeaders::CURRENT_METHOD);
        if ($this->_isSpecialVerb($verb)) {
            $this->_checkSpecialVerb($verb);
            $verb = filter_input(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD);
        }
        return $verb;
    }
    
    /**
     * Computes Requested resource
     * @param string $request the request
     * @return string
     */
    private function _computeResource($request) {
        $resource = 'index';
        
        if (!is_null($request)) {
            $args = explode('.', rtrim($request, '/'));
            $resource = array_shift($args);
        }
        return $resource;
    }

    /**
     * Initializes an ApiParser
     * @param string $request the HTTP request provided by the web server
     */
    public function __construct($request) {
        $resource = $this->_computeResource($request);
        $verb = $this->_computeVerb();
        $args = $this->_computeArgs($verb);
        $this->_request = new Request($resource, $verb, $args);
    }

    /**
     * Gets the parsed request
     * @return Request
     */
    public function getRequest() {
        return $this->_request;
    }

}
