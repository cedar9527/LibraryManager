<?php
sp_autoload_register(function($class) {
    require $class . '.php';
}, true, true);