<?php

namespace Grep\Library;

/**
 *  Logs Manager
 */

class Log
{

    /**
     *  Write requests to log file
     * 
     *  @param int $dateTime
     *  @param array $request
     */
    public static function writeToLog($dateTime, $request): void
    {
        $data = '[' . $dateTime . '] ' . implode($request);
        $fp = fopen(__DIR__ . '/../Logs/server.log', 'a');
        fwrite($fp, $data);
        fclose($fp);
    }
}
