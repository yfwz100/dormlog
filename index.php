<?php
session_start();

require_once 'core/autoload.base.php';

gtf\Router::dispatch(array(
	'verify'	=> 'LoginController',
	'app'		=> 'DormLogController'
));
