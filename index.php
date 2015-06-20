<?php

spl_autoload_register(function($class) {
    $ROOT_URI = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/LibraryManager/';
    $file = $ROOT_URI . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

use api\HttpHeaders;
use api\RequestParser;
use controller\RequestHandler;

if (!filter_has_var(INPUT_SERVER, HttpHeaders::ORIGIN)) {
    $_SERVER[HttpHeaders::ORIGIN] = filter_input(INPUT_SERVER, HttpHeaders::SERVER_NAME);
}

$request = filter_input(INPUT_GET, "request");
$parser = new RequestParser($request);

$handler = new RequestHandler();

$handler->handle($parser);
