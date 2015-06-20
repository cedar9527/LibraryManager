<?php

namespace api;

use Exception;

/**
 * HTTP request parser.
 * */
class RequestParser {

    private $_verb;
    private $_controller;
    private $_args;
    private $_headers;

    private function _isSpecialVerb() {
        return $this->_verb == HttpVerbs::POST && filter_has_var(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD);
    }

    private function _checkSpecialVerb() {
        if (filter_input(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD) != HttpVerbs::PUT && filter_input(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD) != HttpVerbs::DELETE) {
            throw new Exception('Invalid Header', 500);
        }
    }

    private function _argsFromVerb() {
        switch ($this->_verb) {
            case HttpVerbs::POST:
            case HttpVerbs::DELETE:
                $this->_cleanInputs(filter_input_array(INPUT_POST));
                break;
            case HttpVerbs::GET:
            case HttpVerbs::PUT:
                $this->_cleanInputs(filter_input_array(INPUT_GET));
                break;
            default:
                throw new Exception('Invalid Method', 405);
        }
    }

    private function _cleanInputs($data) {
        $cleanInputs = array();
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $cleanInputs[$key] = $this->_cleanInputs($value);
            }
        } else {
            $cleanInputs = trim(strip_tags($data));
        }

        return $cleanInputs;
    }

    private function _manageVerb() {
        $this->_verb = filter_input(INPUT_SERVER, HttpHeaders::CURRENT_METHOD);
        if ($this->_isSpecialVerb()) {
            $this->_checkSpecialVerb();
            $this->_verb = filter_input(INPUT_SERVER, HttpHeaders::SPECIAL_METHOD);
        }

        $this->_argsFromVerb();
    }

    /**
     * Initializes an ApiParser
     * @param string $request the HTTP request provided by the web server
     */
    public function __construct($request) {
        if (is_null($request)) {
            $this->_args = array();
            $this->_controller = 'index';
        } else {
            $this->_args = explode('.', rtrim($request, '/'));
            $this->_controller = array_shift($this->_args);
        }

        $this->_manageVerb();
    }

    /**
     * Gets the HTTP verb
     * @return string
     */
    public function getVerb() {
        return $this->_verb;
    }

    /**
     * Gets the called controller
     * @return string 
     */
    public function getController() {
        return $this->_controller;
    }

    /**
     * Gets the arguments (URI fragments from the web server provided HTTP request)
     * @return string
     */
    public function getArgs() {
        return $this->_args;
    }

    /**
     * Gets the HTTP headers to set
     * @return array an associative array with the request's headers
     */
    public function getHeaders() {
        return $this->_headers;
    }

}
