<?php

namespace Grep\Library;

/** 
 * @Class  : Router
 * @Purpose: Dynamic Routing Manager
 * @Author : Olorunfemi-Ojo Tomiwa
 * @Web    : https://github.com/Tomiwa-Ot
 * @URL    : https://github.com/Tomiwa-Ot/php-router
 * @Wiki   : https://github.com/Tomiwa-Ot/php-router/wiki
 */


class Router
{
    
    /** @var array $dataTypes Acceptable URI datatypes */
    private $dataTypes = array('int', 'string');

    /** @var array $uriList Valid URI(s) */
    private $uriList = array();

    /** @var array $uriListRegExp Regex representation of URI(s) */
    private $uriListRegExp = array();

    /** @var array $uriCallback Callbacks for URI(s) */
    private $uriCallback = array();

    /** @var array $uriVariables Variables found in the URI */
    private $uriVariables = array();

    /** Router constructor method */
    public function __construct()
    {
        $uriList = explode(',', str_replace(' ', '', Config::getEnvProperties('http_method')));
        foreach ($uriList as $uri) {
            $this->uriList[strtoupper($uri)] = array();
            $this->uriListRegExp[strtoupper($uri)] = array();
            $this->uriCallback[strtoupper($uri)] = array();
            $this->uriVariables[strtoupper($uri)] = array();
        }
    }

    /**
     *  Register URI(s) and callbacks
     * 
     *  @param $name
     *  @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists(strtoupper($name), $this->uriList)) {
            $uriRegExp = '/';
            foreach (explode('/', $arguments[0]) as $key => $path) {
                if ($this->stringStartsWith(str_replace(' ', '', $path), '<') && $this->stringEndsWith(str_replace(' ', '', $path), '>')) {
                    if (!in_array(explode(':', substr(str_replace(' ', '', $path), 1, strlen(str_replace(' ', '', $path)) - 1))[0], $this->dataTypes, true)) {
                        return;
                    } else {
                        switch (substr(explode(':', str_replace(' ', '', $path))[0], 1, strlen(explode(':', str_replace(' ', '', $path))[0]))) {
                            case 'int':
                                $uriRegExp .= '\d+';
                                break;
                            default:
                                $uriRegExp .= '\S+';
                        }
                        if ($key + 1 != count(explode('/', $arguments[0]))) {
                            $uriRegExp .= '\/';
                        }
                    }
                } else {
                    if ($key + 1 == count(explode('/', $arguments[0]))) {
                        $uriRegExp .= str_replace(' ', '', $path);
                    } else {
                        $uriRegExp .= str_replace(' ', '', $path) . '\/';
                    }
                }
            }
            $this->uriListRegExp[strtoupper($name)][] = $uriRegExp .= '$/';
            $this->uriList[strtoupper($name)][$uriRegExp] = htmlspecialchars($arguments[0]);
            $this->uriCallback[strtoupper($name)][$uriRegExp] = $arguments[1];
        }
    }

    /** Parse requested route and trigger registered callback */
    public function submit(): void
    {
        if (in_array($_SERVER['REQUEST_METHOD'], explode(',', str_replace(' ', '', Config::getEnvProperties('http_method'))))) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            if($this->stringEndsWith($uri, '/') && $uri !== '/') $uri = substr($uri, 0, strlen($uri) - 1);
            $uriMatches = false;
            $regExp = '';
            foreach ($this->uriListRegExp[$_SERVER['REQUEST_METHOD']] as $regex) {
                if (preg_match($regex, $uri)) {
                    $regExp .= $regex;
                    $uriMatches = true;
                    foreach (explode('/', $this->uriList[$_SERVER['REQUEST_METHOD']][$regex]) as $key => $var) {                    
                        if (strpos($var, ':')) {
                            if (preg_match('/\d+$/', explode('/', $uri)[$key])) {
                                $this->uriVariables[$_SERVER['REQUEST_METHOD']][$regex][str_replace(' ', '', explode(':', substr($var, 0, strlen($var) - 4))[1])] = (int) explode('/', $uri)[$key];
                            } else {
                                $this->uriVariables[$_SERVER['REQUEST_METHOD']][$regex][str_replace(' ', '', explode(':', substr($var, 0, strlen($var) - 4))[1])] = explode('/', $uri)[$key];
                            }
                            require_once __DIR__ . '/URIVariables.php';
                            URIVariables::$reqVars = $this->uriVariables[$_SERVER['REQUEST_METHOD']][$regExp];
                        }
                    }
                    break;
                }
            }
            if ($uriMatches) {
                call_user_func($this->uriCallback[$_SERVER['REQUEST_METHOD']][$regExp]);
            } else {
                require_once __DIR__ . '/../Library/Response.php';
                http_response_code(404);
                render('../defaults/404.php', array('title' => '404 Not Found', 'route' => $_SERVER['REQUEST_URI']));
            }
        } else {
            require_once __DIR__ . '/../Library/Response.php';
            http_response_code(405);
            render('../defaults/405.php', array('title' => '405 Method Not Allowed', 'method' => $_SERVER['REQUEST_METHOD']));
        }
    }

    /**
     *  Check if string starts with a char(s)
     * 
     *  @param $string
     *  @param $startString
     * 
     *  @return bool
     */
    private function stringStartsWith ($string, $startString): bool
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     *  Check if string ends with a char(s)
     * 
     *  @param $string
     *  @param $endString
     * 
     *  @return bool
     */
    private function stringEndsWith($string, $endString): bool
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

}
