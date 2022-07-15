<?php

/**
 *  HTTP Response Functions
 */

/** 
 *  Returns URI variables
 * 
 *  @param string $key
 * 
 *  @return string|int|double
 */
 function reqVar($key)
 {
    require_once __DIR__ . '/URIVariables.php';
    $reqVars = URIVariables::$reqVars;
    return $reqVars[$key];
 }
 
/**
 *  Returns json output
 * 
 *  @param int|string|array|double $data
 *  @param int $statusCode
 */
function json($data, $statusCode): void
{
    require_once __DIR__ . '/Log.php';
    Log::writeToLog(time(), $_SERVER);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($statusCode);
    echo json_encode($data);
}

/**
 *  Returns xml output
 * 
 *  @param int|string|array|double $data
 *  @param int $statusCode
 */
function xml($data, $statusCode): void
{
    require_once __DIR__ . '/Log.php';
    Log::writeToLog(time(), $_SERVER);
    $data = array($data);
    header('Content-Type: application/xml');
    http_response_code($statusCode);
    $xml = new SimpleXMLElement('<root/>');
    array_flip($data);
    array_walk_recursive($data, array($xml, 'addChild'));
    echo $xml->asXML();
}

/**
 *  Render html view
 * 
 *  @param string $view
 *  @param array $data
 */
function render($view, $data = array()): void
{
    require_once __DIR__ . '/Log.php';
    Log::writeToLog(time(), $_SERVER);
    if(count($data)) extract($data);
    require __DIR__ . '/../View/layout/' . $view;
}
