<?php

/**
 *  Dynamic Routing Manager
 */

require __DIR__ . '/../Library/Response.php';
require __DIR__ . '/Config.php';

class Router
{
    
    /**
     *  Acceptable URI datatypes
     */
    private $dataTypes = array('int', 'double', 'string');

    /**
     *  Valid URI(s)
     */
    private static $uriList = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array()
    );

    /**
     *  Regex representation of URI(s)
     */
    private static $uriListRegExp = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array()
    );

    /**
     *  Callbacks for URI(s)
     */
    private static $uriCallback = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'DELETE' => array()
    );

    /**
     *  Register URI(s) and callbacks
     */
    public static function __callStatic($name, $arguments)
    {
        if(array_key_exists(strtoupper($name), self::$uriList))
        {
            $uriRegExp = '/';
            foreach(explode('/', $arguments[0]) as $path)
            {
                $uriRegExp .= '\/';
                if(str_starts_with(trim($path), '<') && str_ends_with(trim($path), '>'))
                {
                    if(!in_array(explode(':', substr(trim($path), 1, strlen(trim($path)) - 1))[0], self::$dataTypes, true))
                    {
                        return;
                    }
                    else
                    {
                        switch(gettype(trim($path)))
                        {
                            case 'integer':
                                $uriRegExp .= '\d+';
                                break;
                            case 'double':
                                $uriRegExp .= '\d+\.\d+';
                            default:
                                $uriRegExp .= '[a-Z]+';
                        }
                    }
                }
                else
                {
                    $uriRegExp .= $path;
                }
            }
            self::$uriList[strtoupper($name)][] = $arguments[0];
            self::$uriListRegExp[strtoupper($name)][] = $uriRegExp;
            self::$uriCallback[strtoupper($name)][] = $arguments[1];
        }
    }

    /**
     *  Parse requested route
     */
    public static function submit()
    {
        if(in_array($_SERVER['REQUEST_METHOD'], Config::getEnvProperties('http_method')))
        {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            $uriMatches = false;
            foreach(self::$uriListRegExp[$_SERVER['REQUEST_METHOD']] as $regex)
            {
                if(preg_match($regex, $uri))
                {
                    $uriMatches = true;
                    break;
                }
            }
            if($uriMatches)
            {
                call_user_func(self::$uriCallback[$_SERVER['REQUEST_METHOD']][$uri]);
            }
            else
            {
                $res = new Response();
                http_response_code(404);
                $res->render('defaults/404.php', array('title' => '404 Not Found'));
            }
        }
        else
        {
            $res = new Response();
            http_response_code(405);
            $res->render('defaults/405.php', array('title' => '405 Method Not Allowed'));
        }
    }
}