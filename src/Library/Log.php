<?php

/**
 *  Logs Manager
 */

class Log
{

    /**
     *  Write requests to log file
     */
    public static function writeToLog($dateTime, $request)
    {
        $data = '[' . $dateTime . ']' . $request;
        $fp = fopen(__DIR__ . '/../Logs/server.log', 'a');
        fwrite($fp, $data);
        fclose($fp);
    }
}