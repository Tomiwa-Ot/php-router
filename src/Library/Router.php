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

    /**
     *  Router constructor method
     */
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
                if ($this->stringStartsWith(trim($path), '<') && $this->stringEndsWith(trim($path), '>')) {
                    if (!in_array(explode(':', substr(trim($path), 1, strlen(trim($path)) - 1))[0], $this->dataTypes, true)) {
                        return;
                    } else {
                        switch (gettype(trim(explode(':', trim($path))[1]))) {
                            case 'integer':
                                $uriRegExp .= '\d+';
                                break;
                            case 'double':
                                $uriRegExp .= '\d+\.\d+';
                            default:
                                $uriRegExp .= '[a-zA-Z]+';
                        }
                    }
                } else {
                    if ($key + 1 == count(explode('/', $arguments[0]))) {
                        $uriRegExp .= trim($path);
                    } else {
                        $uriRegExp .= trim($path) . '\/';
                    }
                }
            }
            $this->uriList[strtoupper($name)][] = $arguments[0];
            $this->uriListRegExp[strtoupper($name)][] = $uriRegExp .= '/';
            $this->uriCallback[strtoupper($name)][$arguments[0]] = $arguments[1];
            //$this->uriCallback[strtoupper($name)][array_search($arguments[0], $this->uriList[strtoupper($name)])] = $arguments[1];
        }
    }

    /**
     *  Parse requested route and trigger registered callback
     */
    public function submit(): void
    {
        if (in_array($_SERVER['REQUEST_METHOD'], explode(',', trim(Config::getEnvProperties('http_method'))))) {
            $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
            if($this->stringEndsWith($uri, '/') && $uri !== '/') $uri = substr($uri, 0, strlen($uri) - 1);
            $uriMatches = false;
            $index;
            print_r($this->uriListRegExp[$_SERVER['REQUEST_METHOD']]);
            foreach ($this->uriListRegExp[$_SERVER['REQUEST_METHOD']] as $key => $regex) {
                if (preg_match($regex, $uri)) {
                    // echo $uri . '<br>'; 
                    // $regExp = $regex;
                    echo $regex . '<br>';
                    $index = $key;
                    echo $index;
                    $uriMatches = true;
                    break;
                }
            }
            print_r($this->uriCallback);
            if ($uriMatches) {
                call_user_func($this->uriCallback[$_SERVER['REQUEST_METHOD']][$uri]);
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