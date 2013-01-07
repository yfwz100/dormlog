<?php

session_start();

require_once 'core/autoload.base.php';

gtf\Router::dispatchOrExit('LoginController');
gtf\Router::dispatch('DormLogController');
