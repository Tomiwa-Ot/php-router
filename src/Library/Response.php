<?php

/**
 *  HTTP Response Functions
 */

/** @var array Variables in URI */
 $reqVar = array();

 
/**
 *  Returns json output
 * 
 *  @param int|string|array|double $data
 *  @param int $statusCode
 */
function json($data, $statusCode)
{
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
function xml($data, $statusCode)
{
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
function render($view, $data = array())
{
    if(count($data)) extract($data);
    require __DIR__ . '/../View/layout/' . $view;
}
