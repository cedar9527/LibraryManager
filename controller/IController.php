<?php
namespace controller;

/**
 * Interface for controller to implement.
 **/
interface IController {
    /**
     * Executes a request
     * @param array $args associative array (string => string) containing the request arguments
     **/
    public function execute(array $args);
}
?>