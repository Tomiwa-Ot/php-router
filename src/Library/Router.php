<?php

/**
 *  Dynamic Routing Manager
 */

require_once __DIR__ . '/../Library/Response.php';
require_once __DIR__ . '/Config.php';

class Router
{
    
    /** @var array $dataTypes Acceptable URI datatypes */
    private $dataTypes = array('int', 'double', 'string');

    /** @var array $uriList Valid URI(s) */
    private $uriList = array();

    /** @var array $uriListRegExp Regex representation of URI(s) */
    private $uriListRegExp = array();

    /** @var array $uriCallback Callbacks for URI(s) */
    private $uriCallback = array();

    /** Router constructor method */
    public function __construct()
    {
        $uriList = explode(',', trim(Config::getEnvProperties('http_method')));
        foreach ($uriList as $uri) {
            $this->uriList[strtoupper($uri)] = array();
            $this->uriListRegExp[strtoupper($uri)] = array();
            $this->uriCallback[strtoupper($uri)] = array();
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
                if ($this->stringStartsWith($path, '<') && $this->stringEndsWith($path, '>')) {
                    if (!in_array(explode(':', substr($path, 1, strlen($path) - 1))[0], $this->dataTypes, true)) {
                        return;
                    } else {
                        switch (substr(explode(':', $path)[0], 1, strlen(explode(':', $path)[0]))) {
                            case 'int':
                                $uriRegExp .= '\d+';
                                break;
                            case 'double':
                                $uriRegExp .= '\d+|\d*\.\d+';
                                break;
                            default:
                                $uriRegExp .= '\S+';
                        }
                    }
                } else {
                    if ($key + 1 == count(explode('/', $arguments[0]))) {
                        $uriRegExp .= $path;
                    } else {
                        $uriRegExp .= $path . '\/';
                    }
                }
            }
            $this->uriList[strtoupper($name)][] = $arguments[0];
            $this->uriListRegExp[strtoupper($name)][] = $uriRegExp .= '$/';
            $this->uriCallback[strtoupper($name)][$uriRegExp] = $arguments[1];
        }
    }

    /** Parse requested route and trigger registered callback */
    public function submit(): void
    {
        if (in_array($_SERVER['REQUEST_METHOD'], explode(',', trim(Config::getEnvProperties('http_method'))))) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            if($this->stringEndsWith($uri, '/') && $uri !== '/') $uri = substr($uri, 0, strlen($uri) - 1);
            $uriMatches = false;
            $regExp = '';
            foreach ($this->uriListRegExp[$_SERVER['REQUEST_METHOD']] as $regex) {
                if (preg_match($regex, $uri)) {
                    $regExp .= $regex;
                    $uriMatches = true;
                    break;
                }
            }
            if ($uriMatches) {
                call_user_func($this->uriCallback[$_SERVER['REQUEST_METHOD']][$regExp]);
            } else {
                http_response_code(404);
                render('../defaults/404.php', array('title' => '404 Not Found'));
                print_r($this->uriList);
                echo '<br>';
                print_r($this->uriListRegExp);
                echo '<br>';
                print_r($this->uriCallback);
            }
        } else {
            http_response_code(405);
            render('../defaults/405.php', array('title' => '405 Method Not Allowed'));
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