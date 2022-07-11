<?php

/**
*   Parse and return .env configuration properties
*/

class Config
{

    /**
     *  Project base directory
    */
    public static $baseDir = __DIR__;


    /**
    *   Serializes the properties of .env in array format
    */
    private static function parseEnvProperties()
    {
        $envProperties = array();
        $vars = file(Config::$baseDir . '/.env', FILE_IGNORE_NEW_LINES);
        foreach($vars as $var)
        {
            $envProperties[explode('=', $var)[0]] = explode('=', $var)[1];
        }
        return $envProperties;
    }


    /**
    *   Return .env property
    */
    public static function getEnvProperties($property = '')
    {
        $envProperties = Config::parseEnvProperties();
        if(empty($property))
        {
            return $envProperties;
        } 
        else 
        {
            return $envProperties[$property];
        }
    }

}