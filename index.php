<?php 
	spl_autoload_register(function($class) {
		$ROOT_URI = $_SERVER['DOCUMENT_ROOT'] . '/LibraryManager/';
		$file = $ROOT_URI . str_replace('\\', '/', $class) . '.php';
		if(file_exists($file)) {
			require_once($file);
		}
	});
	
	use api\HttpHeaders;
	use api\RequestParser;
	use controller\RequestHandler;
	
	if(!array_key_exists(HttpHeaders::ORIGIN, $_SERVER)) {
		$_SERVER[HttpHeaders::ORIGIN] = $_SERVER[HttpHeaders::SERVER_NAME];
	}
	
	$request = $_REQUEST['request'];
	$parser = new RequestParser($request);
	
	$handler = new RequestHandler();
	
	$handler->handle($parser);
?>