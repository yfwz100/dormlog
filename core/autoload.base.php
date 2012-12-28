<?php

spl_autoload_register(function ($name) {
  $path = strtolower(str_replace('\\','/', $name));
  require_once dirname(__FILE__)."/$path.class.php";
});
