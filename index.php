<?php
spl_autoload_register(function($class) {
    require $class . '.php';
}, true, true);
// @todo: Make front controller, and a parser to load input into models / hash password with Dep. Injection, and a REST compliant dispatcher
$controllers = [
];