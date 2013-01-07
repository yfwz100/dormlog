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

    private static function ensureUri($uri=null) {
        if ($uri == null && isset($_SERVER['PATH_INFO'])) {
            $uri = $_SERVER['PATH_INFO'];
        }
        if (!isset($uri) || $uri == '/') {
            $uri = '/index';
        }
        return $uri;
    }

    protected static function dispatch_($ins, $uri = '/index') {
        list($method, $uri) = static::split_($uri);
        return $ins->$method($uri);
    }

    public static function dispatch($res) {
        $uri = static::ensureUri();

        $ctrl = null;
        if (is_array($res)) {
            foreach ($res as $key=>$value) {
                if (strncmp($uri, $key, strlen($key)) == 0) {
                    $uri = substr($uri, strlen($key));
                    $ctrl = $value;
                    break;
                }
            }

            if ($ctrl == null && array_key_exists('*', $res)) {
                $ctrl = $res['*'];
            } else {
                return false;
            }

        } else {
            $ctrl = $res;
        }

        return static::dispatch_(new $ctrl, $uri);
    }

    public static function dispatchOrExit($res) {
        if (static::dispatch($res)) {
            die();
        }
    }

    private static function config($key = null) {
        if (static::$config == null) {
            $config = dirname(dirname(__FILE__)) . '/site.config.php';
            static::$config = require $config;
        }

        if (isset($key)) {
            $key = explode('/', trim($key, '/'));
            $cur = static::$config;
            while ($k = array_shift($key)) {
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

