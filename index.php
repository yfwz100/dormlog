<?php

session_start();

require_once 'core/autoload.base.php';

if (!file_exists('core/admin.config.php')) {
	header("Location: " . gtf\Router::site('user.php'));
}

gtf\Router::dispatchOrExit('LoginController');
gtf\Router::dispatch('DormLogController');
