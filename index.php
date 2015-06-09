<?php 
	use api;
	if(!array_key_exists(HttpHeaders::ORIGIN, $_SERVER)) {
		$_SERVER[HttpHeaders::ORIGIN] = $_SERVER[HttpHeaders::SERVER_NAME];
	}
	
	$request = $_REQUEST['request'];
	$parser = new RequestParser($request);
?>