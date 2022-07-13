<?php

/**
 *   Parse and return .env configuration properties
 */

class Config
{

    /** @var string $baseDir Project base directory */
    public static $baseDir = __DIR__;


    /**
     *   Serializes the properties of .env in array format
     * 
     *  @return array
     */
    private static function parseEnvProperties(): array
    {
        $envProperties = array();
        $vars = file(Config::$baseDir . '/../.env', FILE_IGNORE_NEW_LINES);
        foreach ($vars as $var) {
            if(substr($var, 0, 2) === '##') continue;
            $envProperties[explode('=', $var)[0]] = explode('=', $var)[1];
        }
        return $envProperties;
    }


    /**
     *   Return .env property
     * 
     *  @param string $property
     * 
     *  @return string|array
     */
    public static function getEnvProperties($property = '')
    {
        $envProperties = Config::parseEnvProperties();
        if (empty($property)) {
            return $envProperties;
        } else {
            return $envProperties[$property];
        }
    }

}