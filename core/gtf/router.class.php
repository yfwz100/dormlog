<?php

namespace gtf;

class Router {

  private static $config;

  protected static function split_($str) {
    $path = explode('/', trim($str, '/'));
    $method = array_shift($path);
    $param = $path;
    return array($method, $param);
  }

  public static function verify($cls) {
    $ins = new $cls;
    if ($ins->isVerified()) {
      return true;
    } else {
      static::dispatch_($ins);
      return false;
    }
  }

  protected static function dispatch_($ins, $uri='/index') {
    // obtain the request uri.
    list($method, $uri) = static::split_($uri);
    return $ins->$method($uri);
  }

  public static function dispatch(array $res) {
    if (! isset($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO'] == '/') {
      $uri = '/index';
    } else {
      $uri = $_SERVER['PATH_INFO']; 
    }

    // static::dispatch_(new $cls);
    if (!array_key_exists('verify', $res) || static::dispatch_(new $res['verify'], $uri)) {
      if (is_array($res['app'])) {
        list($method, $urix) = static::split_($uri);

        if (array_key_exists($method, $res['app'])) {
          static::dispatch_(new $res['app'][$method], $urix);
        } else {
          static::dispatch_(new $res['app']['default'], $uri);
        }
      } else {
        static::dispatch_(new $res['app'], $uri);
      }
    }
  }

  private static function config($key=null) {
    if (static::$config == null) {
      $config = dirname(dirname(__FILE__)).'/site.config.php';
      static::$config = require $config;
    }

    if (isset($key)) {
      $key = explode('/', trim($key, '/'));
      $cur = static::$config;
      while ($k=array_shift($key)) {
        $cur = $cur[$k];
      }
      return $cur;
    } else {
      return static::$config;
    }
  }

  public static function res($path) {
    $baseUri = Router::config('resUri');
    return "$baseUri/$path";
  }

  public static function site($path) {
    $baseUri = Router::config('baseUri');
    return "$baseUri/$path";
  }

}

