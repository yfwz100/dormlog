<?php

require '../core/autoload.base.php';

$page = new gtf\Page;
$page->def('message', "So sorry, it seems that you've enter the wrong place. This is not the place that we used to provide serices...");
$page->def('link', gtf\Router::site('index.php'));
$page->template('error.phtml');
